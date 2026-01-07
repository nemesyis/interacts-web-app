<?php
// ===============================================
// File: app/Models/QuizQuestion.php
// ===============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'tb_quiz_question';
    protected $primaryKey = 'question_id';
    public $timestamps = false;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_type',
        'points',
        'correct_answer',
        'options',
        'order_number',
    ];

    protected $casts = [
        'points' => 'decimal:2',
        'options' => 'array',
    ];

    /**
     * Get the quiz
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'quiz_id');
    }
}