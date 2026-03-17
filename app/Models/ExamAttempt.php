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
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'is_passed' => 'boolean',
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

    // --- Deneme Geçmişi tablosu için accessor'lar ---

    public function getPostExamScoreAttribute(): ?float
    {
        return $this->exam_type === 'post_exam' ? $this->score : null;
    }

    public function getPreExamScoreAttribute(): ?float
    {
        return self::where('enrollment_id', $this->enrollment_id)
            ->where('attempt_number', $this->attempt_number)
            ->where('exam_type', 'pre_exam')
            ->whereNotNull('finished_at')
            ->value('score');
    }

    public function getHasPreExamAttribute(): bool
    {
        return self::where('enrollment_id', $this->enrollment_id)
            ->where('attempt_number', $this->attempt_number)
            ->where('exam_type', 'pre_exam')
            ->whereNotNull('finished_at')
            ->exists();
    }
}
