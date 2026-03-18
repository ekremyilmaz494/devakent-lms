<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseSurveyResponse extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'enrollment_id',
        'rating_overall', 'rating_content', 'rating_usefulness',
        'rating_duration', 'feedback',
    ];

    protected function casts(): array
    {
        return [
            'rating_overall'    => 'integer',
            'rating_content'    => 'integer',
            'rating_usefulness' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
}
