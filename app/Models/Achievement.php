<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'extracurricular_id',
        'title',
        'description',
        'date',
        'certificate',
        'level',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the student that owns the achievement.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the extracurricular that is related to the achievement.
     */
    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }
}
