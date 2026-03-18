<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseVideo extends Model
{
    protected $fillable = [
        'course_id', 'title', 'video_path', 'url', 'description',
        'video_duration_seconds', 'sort_order',
        'hls_path', 'transcode_status',
    ];

    protected function casts(): array
    {
        return [
            'video_duration_seconds' => 'integer',
            'sort_order'             => 'integer',
        ];
    }

    public function isHlsReady(): bool
    {
        return $this->transcode_status === 'completed' && $this->hls_path;
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function videoProgress(): HasMany
    {
        return $this->hasMany(VideoProgress::class);
    }
}
