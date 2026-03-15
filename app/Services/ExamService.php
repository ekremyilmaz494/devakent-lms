<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;

class ExamService
{
    public function evaluateExam(ExamAttempt $attempt): array
    {
        $enrollment = $attempt->enrollment;
        $course = $enrollment->course;
        $passingScore = $course->passing_score;

        $isPassed = $attempt->score >= $passingScore;
        $attempt->update(['is_passed' => $isPassed]);

        if ($attempt->exam_type === 'post_exam') {
            return $this->handlePostExamResult($enrollment, $attempt, $isPassed);
        }

        // Pre-exam always advances to video
        return [
            'passed' => true,
            'next_step' => 'video',
            'score' => $attempt->score,
            'message' => 'Ön sınav tamamlandı. Video izleme aşamasına geçebilirsiniz.',
        ];
    }

    private function handlePostExamResult(Enrollment $enrollment, ExamAttempt $attempt, bool $isPassed): array
    {
        $course = $enrollment->course;

        if ($isPassed) {
            $enrollment->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            $certificate = $this->generateCertificate($enrollment, $attempt->score);

            return [
                'passed' => true,
                'next_step' => 'completed',
                'score' => $attempt->score,
                'certificate_number' => $certificate->certificate_number,
                'message' => 'Tebrikler! Eğitimi başarıyla tamamladınız.',
            ];
        }

        $maxAttempts = $course->max_attempts;
        $currentAttempt = $enrollment->current_attempt;

        if ($currentAttempt >= $maxAttempts) {
            $enrollment->update(['status' => 'failed']);

            return [
                'passed' => false,
                'next_step' => 'failed',
                'score' => $attempt->score,
                'attempts_used' => $currentAttempt,
                'max_attempts' => $maxAttempts,
                'message' => 'Sınav hakkınız dolmuştur. Eğitim başarısız olarak kaydedildi.',
            ];
        }

        // Failed but has attempts left - increment attempt and restart from pre_exam
        $enrollment->update([
            'status' => 'in_progress',
            'current_attempt' => $currentAttempt + 1,
        ]);

        return [
            'passed' => false,
            'next_step' => 'pre_exam',
            'score' => $attempt->score,
            'attempts_used' => $currentAttempt,
            'attempts_remaining' => $maxAttempts - $currentAttempt,
            'message' => 'Sınav başarısız. Kalan hakkınız: ' . ($maxAttempts - $currentAttempt) . '. Eğitimi tekrar alabilirsiniz.',
        ];
    }

    private function generateCertificate(Enrollment $enrollment, float $score): Certificate
    {
        $certNumber = 'DVK-' . date('Y') . '-' . str_pad($enrollment->id, 5, '0', STR_PAD_LEFT);

        return Certificate::create([
            'enrollment_id' => $enrollment->id,
            'user_id' => $enrollment->user_id,
            'course_id' => $enrollment->course_id,
            'certificate_number' => $certNumber,
            'final_score' => $score,
            'issued_at' => now(),
        ]);
    }

    public function createAttempt(Enrollment $enrollment, string $examType): ExamAttempt
    {
        return ExamAttempt::create([
            'enrollment_id' => $enrollment->id,
            'attempt_number' => $enrollment->current_attempt ?: 1,
            'exam_type' => $examType,
            'score' => 0,
            'total_questions' => $enrollment->course->questions()->count(),
            'correct_answers' => 0,
            'started_at' => now(),
        ]);
    }

    public function saveAnswer(ExamAttempt $attempt, int $questionId, string $selectedOption): ExamAnswer
    {
        $question = $attempt->enrollment->course->questions()->findOrFail($questionId);
        $isCorrect = $question->correct_option === $selectedOption;

        return ExamAnswer::updateOrCreate(
            [
                'exam_attempt_id' => $attempt->id,
                'question_id' => $questionId,
            ],
            [
                'selected_option' => $selectedOption,
                'is_correct' => $isCorrect,
                'answered_at' => now(),
            ]
        );
    }

    public function finishAttempt(ExamAttempt $attempt): ExamAttempt
    {
        $correctCount = $attempt->answers()->where('is_correct', true)->count();
        $totalQuestions = $attempt->total_questions;
        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;

        $attempt->update([
            'correct_answers' => $correctCount,
            'score' => $score,
            'finished_at' => now(),
        ]);

        return $attempt->fresh();
    }
}
