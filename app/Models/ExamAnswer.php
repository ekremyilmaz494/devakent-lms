<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamAnswer extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'exam_attempt_id', 'question_id',
        'selected_option', 'text_answer', 'is_correct', 'answered_at',
        'manual_score', 'manual_feedback', 'graded_by', 'graded_at',
    ];

    protected function casts(): array
    {
        return [
            'is_correct'   => 'boolean',
            'answered_at'  => 'datetime',
            'graded_at'    => 'datetime',
            'manual_score' => 'decimal:2',
        ];
    }

    public function examAttempt(): BelongsTo
    {
        return $this->belongsTo(ExamAttempt::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function gradedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
