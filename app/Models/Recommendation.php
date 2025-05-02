<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
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
        'score',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'score' => 'float',
    ];

    /**
     * Get the student that owns the recommendation.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the extracurricular that is recommended.
     */
    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }
}
