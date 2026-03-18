<?php

namespace App\Livewire\Staff;

use App\Models\Enrollment;
use App\Models\ExamAttempt;
use App\Services\ExamService;
class ExamEngine extends StaffComponent
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

        $enrollment = Enrollment::with('course.questions')
            ->where('user_id', auth()->id())
            ->findOrFail($enrollmentId);
        $course = $enrollment->course;

        $this->timeRemaining = ($course->exam_duration_minutes ?? 30) * 60;

        // Soru karıştırma — kurs ayarına göre
        $questions = $course->shuffle_questions
            ? $course->questions->shuffle()->values()
            : $course->questions->values();
        $this->questions = $questions->map(function ($q) use ($course) {
            $options = ['a' => $q->option_a, 'b' => $q->option_b, 'c' => $q->option_c, 'd' => $q->option_d];

            if ($course->shuffle_questions && $q->question_type === 'multiple_choice') {
                $filled = array_filter($options, fn ($v) => !is_null($v));
                $keys   = array_keys($filled);
                shuffle($keys);
                $shuffled = [];
                $correctOld = $q->correct_option;
                $newCorrect = null;
                foreach (array_keys($filled) as $i => $origKey) {
                    $newKey = $keys[$i] ?? $origKey;
                    $shuffled[$newKey] = $filled[$origKey];
                    if ($origKey === $correctOld) $newCorrect = $newKey;
                }
                return [
                    'id'             => $q->id,
                    'question_type'  => $q->question_type,
                    'question_text'  => $q->question_text,
                    'option_a'       => $shuffled['a'] ?? null,
                    'option_b'       => $shuffled['b'] ?? null,
                    'option_c'       => $shuffled['c'] ?? null,
                    'option_d'       => $shuffled['d'] ?? null,
                    'correct_option' => $newCorrect ?? $q->correct_option,
                ];
            }

            return [
                'id'             => $q->id,
                'question_type'  => $q->question_type,
                'question_text'  => $q->question_text,
                'option_a'       => $q->option_a,
                'option_b'       => $q->option_b,
                'option_c'       => $q->option_c,
                'option_d'       => $q->option_d,
                'correct_option' => $q->correct_option,
            ];
        })->toArray();

        // Create exam attempt
        $examService = new ExamService();
        $attempt = $examService->createAttempt($enrollment, $examType);
        $this->attemptId = $attempt->id;
    }

    public function selectAnswer(string $answer): void
    {
        if ($this->isFinished) return;

        $question = $this->questions[$this->currentQuestionIndex];
        $this->answers[$question['id']] = $answer;

        // Save immediately to DB
        $attempt = ExamAttempt::find($this->attemptId);
        $examService = new ExamService();
        $examService->saveAnswer($attempt, $question['id'], $answer);
    }

    public function saveTextAnswer(string $text): void
    {
        if ($this->isFinished) return;

        $question = $this->questions[$this->currentQuestionIndex];
        $trimmed = trim($text);
        $this->answers[$question['id']] = $trimmed ?: null;

        if ($trimmed) {
            $attempt = ExamAttempt::find($this->attemptId);
            $examService = new ExamService();
            $examService->saveAnswer($attempt, $question['id'], $trimmed);
        }
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
