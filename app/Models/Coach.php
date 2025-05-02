<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'specialty',
        'certificates',
        'experience',
    ];

    /**
     * Get the user that owns the coach.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the extracurriculars that the coach manages.
     */
    public function extracurriculars()
    {
        return $this->hasMany(Extracurricular::class);
    }
}
