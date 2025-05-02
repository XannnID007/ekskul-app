<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'capacity',
        'schedule',
        'coach_id',
        'location',
        'status',
    ];

    /**
     * Get the coach that manages the extracurricular.
     */
    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    /**
     * Get the students enrolled in the extracurricular.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->withPivot('academic_year', 'status')
            ->withTimestamps();
    }

    /**
     * Get the enrollments for the extracurricular.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the meetings for the extracurricular.
     */
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    /**
     * Get the recommendations for the extracurricular.
     */
    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

    /**
     * Get the achievements for the extracurricular.
     */
    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    /**
     * Check if the extracurricular has available capacity.
     *
     * @return bool
     */
    public function hasAvailableCapacity()
    {
        $enrolledCount = $this->enrollments()
            ->where('status', 'approved')
            ->orWhere('status', 'pending')
            ->count();

        return $enrolledCount < $this->capacity;
    }

    /**
     * Get the number of available slots.
     *
     * @return int
     */
    public function availableSlots()
    {
        $enrolledCount = $this->enrollments()
            ->where('status', 'approved')
            ->orWhere('status', 'pending')
            ->count();

        return max(0, $this->capacity - $enrolledCount);
    }
}
