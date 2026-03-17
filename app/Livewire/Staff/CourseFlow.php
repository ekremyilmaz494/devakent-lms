<?php

namespace App\Livewire\Staff;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\VideoProgress;
use Livewire\Attributes\On;
use Livewire\Component;

class CourseFlow extends Component
{
    public int $courseId;
    public string $step = 'intro'; // intro, pre_exam_warning, pre_exam, video, post_exam_warning, post_exam, result, completed, failed
    public ?array $examResult = null;
    public ?int $currentVideoId = null;

    public function mount(int $courseId): void
    {
        $this->courseId = $courseId;
        $this->determineStep();
    }

    public function getEnrollmentProperty(): ?Enrollment
    {
        return Enrollment::where('user_id', auth()->id())
            ->where('course_id', $this->courseId)
            ->first();
    }

    public function getCourseProperty(): Course
    {
        return Course::with(['category', 'questions', 'videos'])->findOrFail($this->courseId);
    }

    private function determineStep(): void
    {
        $enrollment = $this->enrollment;

        if (!$enrollment) {
            $this->step = 'intro';
            return;
        }

        if ($enrollment->status === 'completed') {
            $this->step = 'completed';
            return;
        }

        if ($enrollment->status === 'failed') {
            $this->step = 'failed';
            return;
        }

        $attemptNumber = $enrollment->current_attempt ?: 1;

        // 1. denemede ön sınav var, 2./3. denemede ön sınav atlanır
        if ($attemptNumber === 1) {
            $preExam = $enrollment->examAttempts()
                ->where('attempt_number', $attemptNumber)
                ->where('exam_type', 'pre_exam')
                ->whereNotNull('finished_at')
                ->first();

            if (!$preExam) {
                $this->step = 'pre_exam_warning';
                return;
            }
        }

        // Çoklu video kontrolü
        $courseVideos = $this->course->videos;

        if ($courseVideos->isNotEmpty()) {
            foreach ($courseVideos as $video) {
                $progress = VideoProgress::where('enrollment_id', $enrollment->id)
                    ->where('course_video_id', $video->id)
                    ->where('attempt_number', $attemptNumber)
                    ->first();

                if (!$progress || !$progress->is_completed) {
                    $this->currentVideoId = $video->id;
                    $this->step = 'video';
                    return;
                }
            }
        }

        // Son sınav kontrolü (tüm videolar tamamlanmış)
        $postExam = $enrollment->examAttempts()
            ->where('attempt_number', $attemptNumber)
            ->where('exam_type', 'post_exam')
            ->whereNotNull('finished_at')
            ->first();

        if (!$postExam) {
            $this->step = 'post_exam_warning';
            return;
        }

        $this->step = 'result';
    }

    public function startCourse(): void
    {
        $enrollment = $this->enrollment;

        if (!$enrollment) {
            $enrollment = Enrollment::create([
                'user_id' => auth()->id(),
                'course_id' => $this->courseId,
                'status' => 'in_progress',
                'current_attempt' => 1,
            ]);
        } elseif ($enrollment->status === 'not_started') {
            $enrollment->update([
                'status' => 'in_progress',
                'current_attempt' => 1,
            ]);
        }

        $this->step = 'pre_exam_warning';
    }

    public function startExam(): void
    {
        // Uyarı ekranından sınava geç
        if ($this->step === 'pre_exam_warning') {
            $this->step = 'pre_exam';
        } elseif ($this->step === 'post_exam_warning') {
            $this->step = 'post_exam';
        }
    }

    #[On('examCompleted')]
    public function onExamCompleted(array $result): void
    {
        $this->examResult = $result;

        if ($result['next_step'] === 'video' && ($result['passed'] ?? false)) {
            // Pre-exam tamamlandı, bir sonraki adıma geç (video veya post_exam_warning)
            $this->determineStep();
        } elseif ($result['next_step'] === 'video' && !($result['passed'] ?? true)) {
            // Post-exam başarısız ama deneme hakkı var — sonuç ekranı göster
            $this->step = 'result';
        } elseif ($result['next_step'] === 'completed') {
            $this->step = 'completed';
        } elseif ($result['next_step'] === 'failed') {
            $this->step = 'failed';
        } else {
            $this->step = $result['next_step'];
        }
    }

    #[On('videoCompleted')]
    public function onVideoCompleted(): void
    {
        // Bir sonraki tamamlanmamış videoyu bul veya hepsi bittiyse post_exam'e geç
        $this->determineStep();
    }

    public function playVideo(int $courseVideoId): void
    {
        $enrollment = $this->enrollment;
        if (!$enrollment) return;

        $courseVideos = $this->course->videos;
        $attemptNumber = $enrollment->current_attempt ?: 1;

        // Hedef videonun index'ini bul
        $targetIndex = $courseVideos->search(fn ($v) => $v->id === $courseVideoId);
        if ($targetIndex === false) return;

        // Sıralı izleme: önceki tüm videoların tamamlanmış olması gerekir
        for ($i = 0; $i < $targetIndex; $i++) {
            $prevVideo = $courseVideos[$i];
            $prevProgress = VideoProgress::where('enrollment_id', $enrollment->id)
                ->where('course_video_id', $prevVideo->id)
                ->where('attempt_number', $attemptNumber)
                ->first();

            if (!$prevProgress || !$prevProgress->is_completed) {
                return; // Önceki video tamamlanmamış, geçiş engelle
            }
        }

        $this->currentVideoId = $courseVideoId;
    }

    public function retryFromBeginning(): void
    {
        $this->examResult = null;
        // 2./3. denemede ön sınav atlanır, determineStep doğru adımı belirler
        $this->determineStep();
    }

    public function getProgressStepsProperty(): array
    {
        $attemptNumber = $this->enrollment?->current_attempt ?: 1;
        $videoCount = $this->course->videos->count();
        $videoLabel = $videoCount > 1 ? "Videolar ({$videoCount})" : 'Video';

        if ($attemptNumber > 1) {
            $steps = [
                ['key' => 'video', 'label' => $videoLabel],
                ['key' => 'post_exam', 'label' => 'Son Sınav'],
            ];

            $currentIndex = match ($this->step) {
                'video' => 0,
                'post_exam_warning', 'post_exam' => 1,
                'result', 'completed' => 2,
                default => -1,
            };
        } else {
            $steps = [
                ['key' => 'pre_exam', 'label' => 'Ön Sınav'],
                ['key' => 'video', 'label' => $videoLabel],
                ['key' => 'post_exam', 'label' => 'Son Sınav'],
            ];

            $currentIndex = match ($this->step) {
                'pre_exam_warning', 'pre_exam' => 0,
                'video' => 1,
                'post_exam_warning', 'post_exam' => 2,
                'result', 'completed' => 3,
                default => -1,
            };
        }

        foreach ($steps as $i => &$s) {
            $s['status'] = $i < $currentIndex ? 'completed' : ($i === $currentIndex ? 'current' : 'pending');
        }

        return $steps;
    }

    public function getPreviousAttemptsProperty(): \Illuminate\Support\Collection
    {
        if (!$this->enrollment) {
            return collect();
        }

        return $this->enrollment->examAttempts()
            ->where('exam_type', 'post_exam')
            ->whereNotNull('finished_at')
            ->orderBy('attempt_number')
            ->get();
    }

    public function render()
    {
        $enrollment = $this->enrollment;

        // Video listesi sidebar'ı için video progress verilerini eager load et
        if ($enrollment) {
            $enrollment->load(['videoProgress' => function ($q) use ($enrollment) {
                $q->where('attempt_number', $enrollment->current_attempt ?: 1);
            }]);
        }

        return view('livewire.staff.course-flow', [
            'course' => $this->course,
            'enrollment' => $enrollment,
            'progressSteps' => $this->progressSteps,
            'previousAttempts' => $this->previousAttempts,
        ]);
    }
}
