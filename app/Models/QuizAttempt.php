<?php
// ===============================================
// File: app/Models/QuizAttempt.php
// ===============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $table = 'tb_quiz_attempt';
    protected $primaryKey = 'attempt_id';

    protected $fillable = [
        'quiz_id',
        'student_id',
        'score',
        'total_points',
        'passed',
        'submitted_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'total_points' => 'decimal:2',
        'passed' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the quiz
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'quiz_id');
    }

    /**
     * Get the student
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');
    }
}