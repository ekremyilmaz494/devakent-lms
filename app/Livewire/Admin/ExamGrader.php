<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Department;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Services\ExamService;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\WithPagination;

class ExamGrader extends AdminComponent
{
    use WithPagination;

    public ?int $selectedAttemptId = null;
    public array $scores    = [];
    public array $feedbacks = [];
    public string $filterCourse     = '';
    public string $filterDepartment = '';

    protected $queryString = ['filterCourse', 'filterDepartment'];

    public function getPendingCountProperty(): int
    {
        return ExamAttempt::where('needs_manual_grading', true)
            ->whereNull('manual_grading_completed_at')
            ->count();
    }

    public function getPendingAttemptsProperty()
    {
        return ExamAttempt::query()
            ->with(['enrollment.user.department', 'enrollment.course'])
            ->where('needs_manual_grading', true)
            ->whereNull('manual_grading_completed_at')
            ->when($this->filterCourse, fn ($q) =>
                $q->whereHas('enrollment', fn ($e) => $e->where('course_id', $this->filterCourse))
            )
            ->when($this->filterDepartment, fn ($q) =>
                $q->whereHas('enrollment.user', fn ($u) => $u->where('department_id', $this->filterDepartment))
            )
            ->orderBy('finished_at', 'asc')
            ->paginate(10);
    }

    public function getCoursesProperty()
    {
        return Course::orderBy('title')->get(['id', 'title']);
    }

    public function getDepartmentsProperty()
    {
        return Department::orderBy('name')->get(['id', 'name']);
    }

    public function selectAttempt(int $attemptId): void
    {
        $this->selectedAttemptId = $attemptId;
        $this->scores    = [];
        $this->feedbacks = [];
        $this->resetErrorBag();
    }

    public function resetSelection(): void
    {
        $this->selectedAttemptId = null;
        $this->scores    = [];
        $this->feedbacks = [];
        $this->resetErrorBag();
    }

    public function updatedFilterCourse(): void
    {
        $this->resetPage();
    }

    public function updatedFilterDepartment(): void
    {
        $this->resetPage();
    }

    public function refreshPending(): void
    {
        // wire:poll tarafından çağrılır — reaktif özellikler otomatik yenilenir
    }

    public function submitGrades(): void
    {
        $attempt = ExamAttempt::with('answers.question')
            ->findOrFail($this->selectedAttemptId);

        $openAnswers = $attempt->answers->filter(fn ($a) => $a->question->isOpenEnded());

        // Validasyon: tüm açık uçlu sorulara puan girilmeli
        foreach ($openAnswers as $answer) {
            $score = $this->scores[$answer->id] ?? null;
            if ($score === null || $score === '') {
                $this->addError('scores', 'Tüm açık uçlu sorulara puan girilmelidir.');
                return;
            }
            if ((float) $score < 0 || (float) $score > 10) {
                $this->addError('scores', 'Puan 0 ile 10 arasında olmalıdır.');
                return;
            }
        }

        DB::transaction(function () use ($openAnswers) {
            foreach ($openAnswers as $answer) {
                ExamAnswer::where('id', $answer->id)->update([
                    'manual_score'    => $this->scores[$answer->id],
                    'manual_feedback' => $this->feedbacks[$answer->id] ?? null,
                    'graded_by'       => auth()->id(),
                    'graded_at'       => now(),
                ]);
            }
        });

        app(ExamService::class)->calculateFinalScore($attempt->fresh());

        $this->resetSelection();
        session()->flash('success', 'Değerlendirme kaydedildi ve öğrenciye bildirim gönderildi.');
    }

    public function render(): View
    {
        return view('livewire.admin.exam-grader', [
            'pendingAttempts' => $this->pendingAttempts,
            'pendingCount'    => $this->pendingCount,
            'courses'         => $this->courses,
            'departments'     => $this->departments,
            'selectedAttempt' => $this->selectedAttemptId
                ? ExamAttempt::with([
                    'enrollment.user.department',
                    'enrollment.course',
                    'answers.question',
                    'answers.gradedBy',
                ])->find($this->selectedAttemptId)
                : null,
        ]);
    }
}
