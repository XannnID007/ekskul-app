<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Interest;
use App\Models\Recommendation;
use App\Models\Extracurricular;
use App\Models\Enrollment;
use App\Services\NaiveBayesRecommender;
use Illuminate\Support\Facades\Validator;

class RecommendationController extends Controller
{
    protected $recommender;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\NaiveBayesRecommender  $recommender
     * @return void
     */
    public function __construct(NaiveBayesRecommender $recommender)
    {
        $this->middleware('auth');
        $this->middleware('siswa');
        $this->recommender = $recommender;
    }

    /**
     * Show the interest survey form.
     *
     * @return \Illuminate\View\View
     */
    public function showSurveyForm()
    {
        $user = Auth::user();
        $student = $user->student;

        // Check if student has already filled the survey
        $hasInterests = $student->interests()->count() > 0;

        // Get interest categories
        $categories = [
            'olahraga' => 'Olahraga',
            'seni' => 'Seni',
            'akademik' => 'Akademik',
            'teknologi' => 'Teknologi',
            'sosial' => 'Sosial',
            'leadership' => 'Leadership',
            'keagamaan' => 'Keagamaan',
        ];

        // Get existing interests if any
        $interests = [];
        foreach ($student->interests as $interest) {
            $interests[$interest->category] = $interest->score;
        }

        return view('siswa.recommendations.survey', compact('student', 'hasInterests', 'categories', 'interests'));
    }

    /**
     * Save the interest survey and generate recommendations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveSurvey(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $validator = Validator::make($request->all(), [
            'interests.*' => 'required|integer|min:1|max:5',
            'academic_score' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update academic score if provided
        if ($request->has('academic_score')) {
            $student->academic_score = $request->academic_score;
            $student->save();
        }

        // Delete existing interests
        $student->interests()->delete();

        // Save new interests
        foreach ($request->interests as $category => $score) {
            Interest::create([
                'student_id' => $student->id,
                'category' => $category,
                'score' => $score,
            ]);
        }

        // Generate recommendations
        $this->recommender->recommend($student->id);

        return redirect()->route('siswa.recommendations.show')
            ->with('success', 'Survei minat berhasil disimpan dan rekomendasi telah diperbarui.');
    }

    /**
     * Show the recommendations.
     *
     * @return \Illuminate\View\View
     */
    public function showRecommendations()
    {
        $user = Auth::user();
        $student = $user->student;

        // Check if student has filled the survey
        $hasInterests = $student->interests()->count() > 0;

        if (!$hasInterests) {
            return redirect()->route('siswa.recommendations.survey')
                ->with('warning', 'Anda harus mengisi survei minat terlebih dahulu.');
        }

        // Get recommendations
        $recommendations = Recommendation::where('student_id', $student->id)
            ->with('extracurricular')
            ->orderBy('score', 'desc')
            ->get();

        // If no recommendations yet, generate them
        if ($recommendations->isEmpty()) {
            $this->recommender->recommend($student->id);
            $recommendations = Recommendation::where('student_id', $student->id)
                ->with('extracurricular')
                ->orderBy('score', 'desc')
                ->get();
        }

        // Get already enrolled extracurriculars
        $enrolledExtracurricularIds = Enrollment::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->pluck('extracurricular_id')
            ->toArray();

        return view('siswa.recommendations.show', compact('student', 'recommendations', 'enrolledExtracurricularIds'));
    }

    /**
     * Enroll in an extracurricular from recommendations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enroll(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $validator = Validator::make($request->all(), [
            'extracurricular_id' => 'required|exists:extracurriculars,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $extracurricularId = $request->extracurricular_id;

        // Check if already enrolled
        $alreadyEnrolled = Enrollment::where('student_id', $student->id)
            ->where('extracurricular_id', $extracurricularId)
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->exists();

        if ($alreadyEnrolled) {
            return back()->with('warning', 'Anda sudah terdaftar dalam ekstrakurikuler ini.');
        }

        // Check if extracurricular has available capacity
        $extracurricular = Extracurricular::findOrFail($extracurricularId);

        if (!$extracurricular->hasAvailableCapacity()) {
            return back()->with('error', 'Kapasitas ekstrakurikuler ini sudah penuh.');
        }

        // Get current academic year
        $academicYear = date('Y') . '/' . (date('Y') + 1);

        // Create enrollment
        Enrollment::create([
            'student_id' => $student->id,
            'extracurricular_id' => $extracurricularId,
            'academic_year' => $academicYear,
            'status' => 'pending',
        ]);

        return redirect()->route('siswa.enrollments.index')
            ->with('success', 'Pendaftaran berhasil. Menunggu persetujuan pembina.');
    }
}
