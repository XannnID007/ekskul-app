<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nis',
        'kelas',
        'gender',
        'birthdate',
        'address',
        'phone',
        'academic_score',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birthdate' => 'date',
        'academic_score' => 'float',
    ];

    /**
     * Get the user that owns the student.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the interests for the student.
     */
    public function interests()
    {
        return $this->hasMany(Interest::class);
    }

    /**
     * Get the enrollments for the student.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the extracurriculars that the student is enrolled in.
     */
    public function extracurriculars()
    {
        return $this->belongsToMany(Extracurricular::class, 'enrollments')
            ->withPivot('academic_year', 'status')
            ->withTimestamps();
    }

    /**
     * Get the recommendations for the student.
     */
    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

    /**
     * Get the achievements for the student.
     */
    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }
}
