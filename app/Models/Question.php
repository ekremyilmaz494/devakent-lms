<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = [
        'course_id', 'question_type', 'question_text',
        'option_a', 'option_b', 'option_c', 'option_d',
        'correct_option', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function isMultipleChoice(): bool
    {
        return $this->question_type === 'multiple_choice';
    }

    public function isTrueFalse(): bool
    {
        return $this->question_type === 'true_false';
    }

    public function isOpenEnded(): bool
    {
        return $this->question_type === 'open_ended';
    }

    public function isAutoGraded(): bool
    {
        return in_array($this->question_type, ['multiple_choice', 'true_false']);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
