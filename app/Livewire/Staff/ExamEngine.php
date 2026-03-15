<?php

namespace App\Livewire\Staff;

use App\Models\Enrollment;
use App\Models\ExamAttempt;
use App\Services\ExamService;
use Livewire\Component;

class ExamEngine extends Component
{
    public int $enrollmentId;
    public string $examType; // pre_exam or post_exam
    public int $currentQuestionIndex = 0;
    public array $answers = [];
    public array $questions = [];
    public ?int $attemptId = null;
    public int $timeRemaining = 0;
    public bool $isFinished = false;
    public ?array $result = null;

    public function mount(int $enrollmentId, string $examType): void
    {
        $this->enrollmentId = $enrollmentId;
        $this->examType = $examType;

        $enrollment = Enrollment::with('course.questions')->findOrFail($enrollmentId);
        $course = $enrollment->course;

        $this->timeRemaining = ($course->exam_duration_minutes ?? 30) * 60;

        // Get shuffled questions
        $questions = $course->questions->shuffle()->values();
        $this->questions = $questions->map(fn($q) => [
            'id' => $q->id,
            'question_text' => $q->question_text,
            'option_a' => $q->option_a,
            'option_b' => $q->option_b,
            'option_c' => $q->option_c,
            'option_d' => $q->option_d,
        ])->toArray();

        // Create exam attempt
        $examService = new ExamService();
        $attempt = $examService->createAttempt($enrollment, $examType);
        $this->attemptId = $attempt->id;
    }

    public function selectAnswer(string $option): void
    {
        if ($this->isFinished) return;

        $question = $this->questions[$this->currentQuestionIndex];
        $this->answers[$question['id']] = $option;

        // Save immediately to DB
        $attempt = ExamAttempt::find($this->attemptId);
        $examService = new ExamService();
        $examService->saveAnswer($attempt, $question['id'], $option);
    }

    public function nextQuestion(): void
    {
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion(): void
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function goToQuestion(int $index): void
    {
        if ($index >= 0 && $index < count($this->questions)) {
            $this->currentQuestionIndex = $index;
        }
    }

    public function submitExam(): void
    {
        if ($this->isFinished) return;

        $this->isFinished = true;

        $examService = new ExamService();
        $attempt = ExamAttempt::find($this->attemptId);

        // Finish and calculate score
        $attempt = $examService->finishAttempt($attempt);

        // Evaluate the result
        $result = $examService->evaluateExam($attempt);

        $this->result = $result;

        // Dispatch event to parent CourseFlow
        $this->dispatch('examCompleted', result: $result);
    }

    public function timeUp(): void
    {
        $this->submitExam();
    }

    public function render()
    {
        $currentQuestion = $this->questions[$this->currentQuestionIndex] ?? null;
        $answeredCount = count($this->answers);
        $totalQuestions = count($this->questions);

        return view('livewire.staff.exam-engine', [
            'currentQuestion' => $currentQuestion,
            'answeredCount' => $answeredCount,
            'totalQuestions' => $totalQuestions,
        ]);
    }
}
