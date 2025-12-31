<?php
// ===============================================
// File: app/Models/Quiz.php
// ===============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'tb_quiz';
    protected $primaryKey = 'quiz_id';

    protected $fillable = [
        'appointment_id',
        'quiz_title',
        'description',
        'time_limit_minutes',
        'passing_score',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'passing_score' => 'decimal:2',
    ];

    /**
     * Get the appointment
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    /**
     * Get quiz questions
     */
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id', 'quiz_id');
    }

    /**
     * Get quiz attempts
     */
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_id', 'quiz_id');
    }
}