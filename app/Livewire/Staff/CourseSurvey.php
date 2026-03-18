<?php

namespace App\Livewire\Staff;

use App\Models\CourseSurveyResponse;
use App\Models\Enrollment;
class CourseSurvey extends StaffComponent
{
    public int $enrollmentId;
    public int $courseId;

    public int $ratingOverall = 0;
    public int $ratingContent = 0;
    public int $ratingUsefulness = 0;
    public string $ratingDuration = 'appropriate';
    public string $feedback = '';

    public bool $submitted = false;
    public bool $alreadyAnswered = false;

    public function mount(int $enrollmentId, int $courseId): void
    {
        Enrollment::where('user_id', auth()->id())
            ->where('id', $enrollmentId)
            ->where('course_id', $courseId)
            ->firstOrFail();

        $this->enrollmentId = $enrollmentId;
        $this->courseId = $courseId;

        $this->alreadyAnswered = CourseSurveyResponse::where('user_id', auth()->id())
            ->where('course_id', $courseId)
            ->exists();
    }

    protected function rules(): array
    {
        return [
            'ratingOverall'    => 'required|integer|min:1|max:5',
            'ratingContent'    => 'required|integer|min:1|max:5',
            'ratingUsefulness' => 'required|integer|min:1|max:5',
            'ratingDuration'   => 'required|in:too_short,appropriate,too_long',
            'feedback'         => 'nullable|string|max:1000',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        if ($this->alreadyAnswered) {
            return;
        }

        CourseSurveyResponse::create([
            'user_id'          => auth()->id(),
            'course_id'        => $this->courseId,
            'enrollment_id'    => $this->enrollmentId,
            'rating_overall'   => $this->ratingOverall,
            'rating_content'   => $this->ratingContent,
            'rating_usefulness' => $this->ratingUsefulness,
            'rating_duration'  => $this->ratingDuration,
            'feedback'         => trim($this->feedback) ?: null,
        ]);

        $this->submitted = true;
        $this->dispatch('surveyCompleted');
    }

    public function skip(): void
    {
        $this->dispatch('surveySkipped');
    }

    public function render()
    {
        return view('livewire.staff.course-survey');
    }
}
