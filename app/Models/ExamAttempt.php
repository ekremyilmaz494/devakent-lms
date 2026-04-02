<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamAttempt extends Model
{
    protected $fillable = [
        'enrollment_id', 'attempt_number', 'exam_type',
        'score', 'total_questions', 'correct_answers',
        'started_at', 'finished_at', 'is_passed',
        'needs_manual_grading', 'manual_grading_completed_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at'                  => 'datetime',
            'finished_at'                 => 'datetime',
            'is_passed'                   => 'boolean',
            'needs_manual_grading'        => 'boolean',
            'manual_grading_completed_at' => 'datetime',
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class);
    }

    public function getPostExamScoreAttribute(): ?float
    {
        return $this->exam_type === 'post_exam' ? $this->score : null;
    }
}
