<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Extracurricular;
use App\Models\Enrollment;
use App\Models\Interest;
use App\Models\Recommendation;
use Illuminate\Support\Facades\Log;

class NaiveBayesRecommender
{
     protected $trainingData;
     protected $priorProbabilities;
     protected $conditionalProbabilities;
     protected $features = [
          'gender',
          'academic_score',
          'interests',
     ];

     /**
      * Train the model with historical data.
      *
      * @param array $trainingData
      * @return void
      */
     public function train()
     {
          // Collect historical data
          $this->collectTrainingData();

          // Calculate prior probabilities
          $this->calculatePriorProbabilities();

          // Calculate conditional probabilities
          $this->calculateConditionalProbabilities();

          return true;
     }

     /**
      * Collect training data from successful enrollments.
      *
      * @return void
      */
     protected function collectTrainingData()
     {
          $this->trainingData = [];

          // Get successful enrollments (status = 'approved' or 'completed')
          $enrollments = Enrollment::with(['student', 'extracurricular', 'student.interests'])
               ->whereIn('status', ['approved', 'completed'])
               ->get();

          foreach ($enrollments as $enrollment) {
               $student = $enrollment->student;
               $extracurricular = $enrollment->extracurricular;

               // Skip if student or extracurricular is null
               if (!$student || !$extracurricular) {
                    continue;
               }

               // Prepare feature values
               $featureValues = [
                    'gender' => $student->gender,
                    'academic_score' => $this->discretizeAcademicScore($student->academic_score),
                    'interests' => $this->getStudentInterests($student)
               ];

               // Add to training data
               $this->trainingData[] = [
                    'features' => $featureValues,
                    'extracurricular_id' => $extracurricular->id,
                    'extracurricular_name' => $extracurricular->name
               ];
          }
     }

     /**
      * Calculate prior probabilities for each extracurricular.
      *
      * @return void
      */
     protected function calculatePriorProbabilities()
     {
          $this->priorProbabilities = [];

          // Count total samples
          $totalSamples = count($this->trainingData);

          if ($totalSamples == 0) {
               return;
          }

          // Count samples for each extracurricular
          $extracurricularCounts = [];
          foreach ($this->trainingData as $data) {
               $extracurricularId = $data['extracurricular_id'];

               if (!isset($extracurricularCounts[$extracurricularId])) {
                    $extracurricularCounts[$extracurricularId] = 0;
               }

               $extracurricularCounts[$extracurricularId]++;
          }

          // Calculate prior probability for each extracurricular
          foreach ($extracurricularCounts as $extracurricularId => $count) {
               $this->priorProbabilities[$extracurricularId] = $count / $totalSamples;
          }
     }

     /**
      * Calculate conditional probabilities for each feature.
      *
      * @return void
      */
     protected function calculateConditionalProbabilities()
     {
          $this->conditionalProbabilities = [];

          // Get all extracurricular IDs
          $extracurricularIds = array_keys($this->priorProbabilities);

          // For each extracurricular
          foreach ($extracurricularIds as $extracurricularId) {
               $this->conditionalProbabilities[$extracurricularId] = [];

               // Get samples for this extracurricular
               $samples = array_filter($this->trainingData, function ($data) use ($extracurricularId) {
                    return $data['extracurricular_id'] == $extracurricularId;
               });

               $sampleCount = count($samples);

               // For each feature
               foreach ($this->features as $feature) {
                    // Special handling for interests
                    if ($feature == 'interests') {
                         $this->calculateInterestConditionalProbabilities($extracurricularId, $samples);
                         continue;
                    }

                    // Get all unique values for this feature
                    $featureValues = array_unique(array_column(array_column($samples, 'features'), $feature));

                    // For each feature value
                    foreach ($featureValues as $value) {
                         $valueCount = count(array_filter($samples, function ($data) use ($feature, $value) {
                              return $data['features'][$feature] == $value;
                         }));

                         // Apply Laplace smoothing
                         $probability = ($valueCount + 1) / ($sampleCount + count($featureValues));

                         // Store probability
                         $this->conditionalProbabilities[$extracurricularId][$feature][$value] = $probability;
                    }
               }
          }
     }

     /**
      * Calculate conditional probabilities for interests.
      *
      * @param int $extracurricularId
      * @param array $samples
      * @return void
      */
     protected function calculateInterestConditionalProbabilities($extracurricularId, $samples)
     {
          $this->conditionalProbabilities[$extracurricularId]['interests'] = [];

          $sampleCount = count($samples);

          // Get all interest categories
          $interestCategories = Interest::distinct('category')->pluck('category')->toArray();

          // For each interest category
          foreach ($interestCategories as $category) {
               // Count samples with high score in this category
               $highScoreCount = count(array_filter($samples, function ($data) use ($category) {
                    return isset($data['features']['interests'][$category]) &&
                         $data['features']['interests'][$category] >= 3; // High score threshold
               }));

               // Apply Laplace smoothing
               $probability = ($highScoreCount + 1) / ($sampleCount + 2); // 2 possible values (high/low)

               // Store probability
               $this->conditionalProbabilities[$extracurricularId]['interests'][$category] = $probability;
          }
     }

     /**
      * Generate recommendations for a student.
      *
      * @param int $studentId
      * @return array
      */
     public function recommend($studentId)
     {
          // Train model if not trained
          if (empty($this->priorProbabilities)) {
               $this->train();
          }

          // Get student data
          $student = Student::with('interests')->findOrFail($studentId);

          // Prepare student features
          $studentFeatures = [
               'gender' => $student->gender,
               'academic_score' => $this->discretizeAcademicScore($student->academic_score),
               'interests' => $this->getStudentInterests($student)
          ];

          // Calculate posterior probability for each extracurricular
          $posteriorProbabilities = [];

          // Get all extracurricular options
          $extracurriculars = Extracurricular::where('status', 'active')->get();

          foreach ($extracurriculars as $extracurricular) {
               $extracurricularId = $extracurricular->id;

               // If extracurricular not in training data, assign a small prior probability
               if (!isset($this->priorProbabilities[$extracurricularId])) {
                    $priorProbability = 0.1; // Small default value
               } else {
                    $priorProbability = $this->priorProbabilities[$extracurricularId];
               }

               // Start with prior probability
               $posteriorProbability = $priorProbability;

               // Multiply by conditional probabilities
               foreach ($this->features as $feature) {
                    if ($feature == 'interests') {
                         $posteriorProbability *= $this->calculateInterestsProbability($extracurricularId, $studentFeatures['interests']);
                         continue;
                    }

                    $featureValue = $studentFeatures[$feature];

                    // If conditional probability exists
                    if (isset($this->conditionalProbabilities[$extracurricularId][$feature][$featureValue])) {
                         $posteriorProbability *= $this->conditionalProbabilities[$extracurricularId][$feature][$featureValue];
                    } else {
                         // Apply Laplace smoothing for unseen values
                         $totalValues = count($this->conditionalProbabilities[$extracurricularId][$feature] ?? []) + 1;
                         $posteriorProbability *= 1 / ($totalValues);
                    }
               }

               // Store posterior probability
               $posteriorProbabilities[$extracurricularId] = [
                    'extracurricular_id' => $extracurricularId,
                    'name' => $extracurricular->name,
                    'score' => $posteriorProbability,
                    'description' => $extracurricular->description,
                    'capacity' => $extracurricular->capacity,
                    'available_slots' => $extracurricular->availableSlots(),
               ];
          }

          // Sort by probability (descending)
          usort($posteriorProbabilities, function ($a, $b) {
               return $b['score'] <=> $a['score'];
          });

          // Save recommendations to database
          $this->saveRecommendations($studentId, $posteriorProbabilities);

          return $posteriorProbabilities;
     }

     /**
      * Calculate probability based on student interests.
      *
      * @param int $extracurricularId
      * @param array $studentInterests
      * @return float
      */
     protected function calculateInterestsProbability($extracurricularId, $studentInterests)
     {
          $probability = 1.0;

          // If no conditional probabilities for this extracurricular, return default
          if (!isset($this->conditionalProbabilities[$extracurricularId]['interests'])) {
               return $probability;
          }

          foreach ($studentInterests as $category => $score) {
               if (isset($this->conditionalProbabilities[$extracurricularId]['interests'][$category])) {
                    if ($score >= 3) { // High score threshold
                         $probability *= $this->conditionalProbabilities[$extracurricularId]['interests'][$category];
                    } else {
                         $probability *= (1 - $this->conditionalProbabilities[$extracurricularId]['interests'][$category]);
                    }
               }
          }

          return $probability;
     }

     /**
      * Discretize academic score into categories.
      *
      * @param float $score
      * @return string
      */
     protected function discretizeAcademicScore($score)
     {
          if ($score === null) {
               return 'unknown';
          }

          if ($score >= 85) {
               return 'high';
          } else if ($score >= 70) {
               return 'medium';
          } else {
               return 'low';
          }
     }

     /**
      * Get student interests as an associative array.
      *
      * @param Student $student
      * @return array
      */
     protected function getStudentInterests($student)
     {
          $interests = [];

          foreach ($student->interests as $interest) {
               $interests[$interest->category] = $interest->score;
          }

          return $interests;
     }

     /**
      * Save recommendations to database.
      *
      * @param int $studentId
      * @param array $recommendations
      * @return void
      */
     protected function saveRecommendations($studentId, $recommendations)
     {
          // Delete existing recommendations
          Recommendation::where('student_id', $studentId)->delete();

          // Save new recommendations
          foreach ($recommendations as $recommendation) {
               Recommendation::create([
                    'student_id' => $studentId,
                    'extracurricular_id' => $recommendation['extracurricular_id'],
                    'score' => $recommendation['score'],
               ]);
          }
     }
}
