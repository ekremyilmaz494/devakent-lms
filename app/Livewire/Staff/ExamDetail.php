<?php

namespace App\Livewire\Staff;

use App\Models\ExamAttempt;
use Livewire\Component;

class ExamDetail extends Component
{
    public int $attemptId;
    public bool $showModal = false;

    public function mount(int $attemptId): void
    {
        $this->attemptId = $attemptId;
    }

    public function openDetail(): void
    {
        $this->showModal = true;
    }

    public function render()
    {
        $attempt = ExamAttempt::with(['answers.question', 'enrollment.course'])
            ->findOrFail($this->attemptId);

        // Sadece kendi sınavını görebilsin
        if ($attempt->enrollment->user_id !== auth()->id()) {
            abort(403);
        }

        $answers = $attempt->answers->map(function ($answer) {
            $q = $answer->question;
            return [
                'question_text' => $q->question_text,
                'selected_option' => $answer->selected_option,
                'correct_option' => $q->correct_option,
                'is_correct' => $answer->is_correct,
                'options' => [
                    'a' => $q->option_a,
                    'b' => $q->option_b,
                    'c' => $q->option_c,
                    'd' => $q->option_d,
                ],
            ];
        });

        return view('livewire.staff.exam-detail', [
            'attempt' => $attempt,
            'answers' => $answers,
        ]);
    }
}
