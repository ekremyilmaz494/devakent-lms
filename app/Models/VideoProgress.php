<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoProgress extends Model
{
    const CREATED_AT = null;

    protected $table = 'video_progress';

    protected $fillable = [
        'enrollment_id', 'course_video_id', 'attempt_number',
        'watched_seconds', 'total_seconds',
        'is_completed', 'last_position',
    ];

    protected function casts(): array
    {
        return ['is_completed' => 'boolean'];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function courseVideo(): BelongsTo
    {
        return $this->belongsTo(CourseVideo::class);
    }
}
