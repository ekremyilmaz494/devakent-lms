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
    public string $step = 'intro'; // intro, pre_exam, video, post_exam, result, completed, failed
    public ?array $examResult = null;

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
        return Course::with(['category', 'questions'])->findOrFail($this->courseId);
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

        // Check pre-exam status for current attempt
        $preExam = $enrollment->examAttempts()
            ->where('attempt_number', $attemptNumber)
            ->where('exam_type', 'pre_exam')
            ->whereNotNull('finished_at')
            ->first();

        if (!$preExam) {
            $this->step = 'pre_exam';
            return;
        }

        // Check video status for current attempt
        $videoProgress = VideoProgress::where('enrollment_id', $enrollment->id)
            ->where('attempt_number', $attemptNumber)
            ->first();

        if (!$videoProgress || !$videoProgress->is_completed) {
            $this->step = 'video';
            return;
        }

        // Check post-exam status for current attempt
        $postExam = $enrollment->examAttempts()
            ->where('attempt_number', $attemptNumber)
            ->where('exam_type', 'post_exam')
            ->whereNotNull('finished_at')
            ->first();

        if (!$postExam) {
            $this->step = 'post_exam';
            return;
        }

        // If post-exam finished but enrollment not completed/failed, show result
        $this->step = 'result';
    }

    public function startCourse(): void
    {
        $enrollment = $this->enrollment;

        if (!$enrollment) {
            // Auto-enroll if not yet enrolled (shouldn't normally happen, but safety net)
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

        $this->step = 'pre_exam';
    }

    #[On('examCompleted')]
    public function onExamCompleted(array $result): void
    {
        $this->examResult = $result;

        if ($result['next_step'] === 'video') {
            $this->step = 'video';
        } elseif ($result['next_step'] === 'completed') {
            $this->step = 'completed';
        } elseif ($result['next_step'] === 'failed') {
            $this->step = 'failed';
        } elseif ($result['next_step'] === 'pre_exam') {
            // Failed but can retry
            $this->step = 'result';
        } else {
            $this->step = $result['next_step'];
        }
    }

    #[On('videoCompleted')]
    public function onVideoCompleted(): void
    {
        $this->step = 'post_exam';
    }

    public function retryFromBeginning(): void
    {
        $this->examResult = null;
        $this->step = 'pre_exam';
    }

    public function getProgressStepsProperty(): array
    {
        $steps = [
            ['key' => 'pre_exam', 'label' => 'Ön Sınav'],
            ['key' => 'video', 'label' => 'Video'],
            ['key' => 'post_exam', 'label' => 'Son Sınav'],
        ];

        $currentIndex = match ($this->step) {
            'pre_exam' => 0,
            'video' => 1,
            'post_exam' => 2,
            'result', 'completed' => 3,
            default => -1,
        };

        foreach ($steps as $i => &$s) {
            $s['status'] = $i < $currentIndex ? 'completed' : ($i === $currentIndex ? 'current' : 'pending');
        }

        return $steps;
    }

    public function render()
    {
        return view('livewire.staff.course-flow', [
            'course' => $this->course,
            'enrollment' => $this->enrollment,
            'progressSteps' => $this->progressSteps,
        ]);
    }
}
