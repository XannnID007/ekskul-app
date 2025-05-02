<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'extracurricular_id',
        'title',
        'description',
        'date',
        'duration',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
        'duration' => 'integer',
    ];

    /**
     * Get the extracurricular that owns the meeting.
     */
    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }

    /**
     * Get the attendances for the meeting.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
