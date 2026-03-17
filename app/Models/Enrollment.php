<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'status',
        'current_attempt', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
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

    public function examAttempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function videoProgress(): HasMany
    {
        return $this->hasMany(VideoProgress::class);
    }

    /**
     * Mevcut deneme döngüsünde tüm videoların tamamlanıp tamamlanmadığını kontrol eder.
     */
    public function allVideosCompleted(): bool
    {
        $totalVideos = $this->course->videos()->count();

        if ($totalVideos === 0) {
            return true;
        }

        $completedVideos = $this->videoProgress()
            ->where('attempt_number', $this->current_attempt ?: 1)
            ->where('is_completed', true)
            ->count();

        return $completedVideos >= $totalVideos;
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }
}
