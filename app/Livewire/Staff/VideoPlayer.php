<?php

namespace App\Livewire\Staff;

use App\Models\CourseVideo;
use App\Models\Enrollment;
use App\Models\VideoProgress;
use Livewire\Component;

class VideoPlayer extends Component
{
    public int $enrollmentId;
    public int $courseVideoId;
    public string $videoTitle = '';
    public string $videoPath = '';
    public string $hlsUrl = '';
    public bool $useHls = false;
    public int $totalSeconds = 0;
    public int $watchedSeconds = 0;
    public int $lastPosition = 0;
    public bool $isCompleted = false;

    public function mount(int $enrollmentId, int $courseVideoId): void
    {
        $this->enrollmentId = $enrollmentId;
        $this->courseVideoId = $courseVideoId;

        $enrollment = Enrollment::findOrFail($enrollmentId);
        $courseVideo = CourseVideo::findOrFail($courseVideoId);

        $this->videoTitle = $courseVideo->title;
        $this->videoPath = $courseVideo->video_path ?? '';
        $this->totalSeconds = $courseVideo->video_duration_seconds ?? 0;

        // HLS streaming support
        if ($courseVideo->isHlsReady()) {
            $this->useHls = true;
            $this->hlsUrl = route('stream.playlist', $courseVideo);
        }

        // Load existing progress for this specific video
        $progress = VideoProgress::where('enrollment_id', $enrollmentId)
            ->where('course_video_id', $courseVideoId)
            ->where('attempt_number', $enrollment->current_attempt ?: 1)
            ->first();

        if ($progress) {
            $this->watchedSeconds = $progress->watched_seconds;
            $this->lastPosition = $progress->last_position;
            $this->isCompleted = $progress->is_completed;
        }
    }

    public function setDuration(int $duration): void
    {
        if ($this->totalSeconds === 0 && $duration > 0) {
            $this->totalSeconds = $duration;

            // DB'de de güncelle
            $courseVideo = CourseVideo::find($this->courseVideoId);
            if ($courseVideo && $courseVideo->video_duration_seconds === 0) {
                $courseVideo->update(['video_duration_seconds' => $duration]);
            }
        }
    }

    public function updateProgress(int $currentPosition, int $watchedSeconds): void
    {
        if ($this->isCompleted) return;

        $enrollment = Enrollment::find($this->enrollmentId);
        $attemptNumber = $enrollment->current_attempt ?: 1;

        $this->watchedSeconds = max($this->watchedSeconds, $watchedSeconds);
        $this->lastPosition = $currentPosition;

        // Check if video is completed (watched at least 90% of total duration)
        // totalSeconds=0 ise süre bilgisi henüz yok, otomatik tamamlama yapma
        if ($this->totalSeconds > 0) {
            $completionThreshold = $this->totalSeconds * 0.9;
            $this->isCompleted = $this->watchedSeconds >= $completionThreshold;
        }

        VideoProgress::updateOrCreate(
            [
                'enrollment_id' => $this->enrollmentId,
                'course_video_id' => $this->courseVideoId,
                'attempt_number' => $attemptNumber,
            ],
            [
                'watched_seconds' => $this->watchedSeconds,
                'total_seconds' => $this->totalSeconds,
                'is_completed' => $this->isCompleted,
                'last_position' => $this->lastPosition,
            ]
        );

        if ($this->isCompleted) {
            $this->dispatch('videoCompleted');
        }
    }

    public function markCompleted(): void
    {
        // Manual completion for demo/testing or when video has no duration info
        $enrollment = Enrollment::find($this->enrollmentId);
        $attemptNumber = $enrollment->current_attempt ?: 1;

        $this->isCompleted = true;

        VideoProgress::updateOrCreate(
            [
                'enrollment_id' => $this->enrollmentId,
                'course_video_id' => $this->courseVideoId,
                'attempt_number' => $attemptNumber,
            ],
            [
                'watched_seconds' => $this->totalSeconds,
                'total_seconds' => $this->totalSeconds,
                'is_completed' => true,
                'last_position' => $this->totalSeconds,
            ]
        );

        $this->dispatch('videoCompleted');
    }

    public function render()
    {
        $progressPercent = $this->totalSeconds > 0
            ? min(100, round(($this->watchedSeconds / $this->totalSeconds) * 100))
            : 0;

        return view('livewire.staff.video-player', [
            'progressPercent' => $progressPercent,
        ]);
    }
}
