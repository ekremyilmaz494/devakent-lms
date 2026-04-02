<?php

namespace App\Services;

use App\Jobs\GenerateCertificateJob;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\User;
use App\Notifications\ExamResultNotification;
use Illuminate\Support\Facades\DB;

class ExamService
{
    private NotificationService $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    public function evaluateExam(ExamAttempt $attempt): array
    {
        // Açık uçlu sorular değerlendirme bekliyorsa is_passed hesaplama
        if ($attempt->needs_manual_grading) {
            return [
                'passed'    => null,
                'next_step' => 'pending_review',
                'score'     => $attempt->score,
                'message'   => 'Açık uçlu sorularınız değerlendirme bekliyor. Sonuç değerlendirme tamamlandıktan sonra bildirilecektir.',
            ];
        }

        $enrollment = $attempt->enrollment;
        $course = $enrollment->course;
        $passingScore = $course->passing_score;

        $isPassed = $attempt->score >= $passingScore;
        $attempt->update(['is_passed' => $isPassed]);

        if ($attempt->exam_type === 'post_exam') {
            // E-posta bildirimi gönder
            $user = User::find($enrollment->user_id);
            $user?->notify(new ExamResultNotification($attempt, $course->title));

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
            $certificate = DB::transaction(function () use ($enrollment, $attempt) {
                $enrollment->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
                return $this->generateCertificate($enrollment, $attempt->score);
            });

            $this->notificationService->sendToUser(
                $enrollment->user_id,
                'Sertifikanız Hazır!',
                '"' . $course->title . '" eğitimini %' . $attempt->score . ' başarıyla tamamladınız. Sertifikanız oluşturuldu.',
                'success'
            );

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
            DB::transaction(fn () => $enrollment->update(['status' => 'failed']));

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
        DB::transaction(fn () => $enrollment->update([
            'status' => 'in_progress',
            'current_attempt' => $currentAttempt + 1,
        ]));

        return [
            'passed' => false,
            'next_step' => 'video', // 2./3. denemede ön sınav atlanır, direkt video
            'score' => $attempt->score,
            'attempts_used' => $currentAttempt,
            'attempts_remaining' => $maxAttempts - $currentAttempt,
            'message' => 'Sınav başarısız. Kalan hakkınız: ' . ($maxAttempts - $currentAttempt) . '. Eğitimi tekrar alabilirsiniz.',
        ];
    }

    private function generateCertificate(Enrollment $enrollment, float $score): Certificate
    {
        // Pessimistic lock ile mükerrerlik kontrolü — eşzamanlı isteklerde race condition önler
        $existing = Certificate::where('user_id', $enrollment->user_id)
            ->where('course_id', $enrollment->course_id)
            ->lockForUpdate()
            ->first();

        if ($existing) {
            return $existing;
        }

        // Sıralı numara üret
        $lastId = (int) Certificate::max('id');
        $certNumber = 'DVK-' . date('Y') . '-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        $certificate = Certificate::create([
            'enrollment_id' => $enrollment->id,
            'user_id' => $enrollment->user_id,
            'course_id' => $enrollment->course_id,
            'certificate_number' => $certNumber,
            'final_score' => $score,
            'issued_at' => now(),
        ]);

        GenerateCertificateJob::dispatch($certificate);

        // Rozet kontrolü
        app(BadgeService::class)->checkAndAwardBadges($enrollment->user_id, $enrollment);

        return $certificate;
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

    public function saveAnswer(ExamAttempt $attempt, int $questionId, string $answer): ExamAnswer
    {
        $question = $attempt->enrollment->course->questions()->findOrFail($questionId);

        if ($question->isOpenEnded()) {
            // Açık uçlu: metin cevabı kaydet, otomatik puanlanamaz
            return ExamAnswer::updateOrCreate(
                ['exam_attempt_id' => $attempt->id, 'question_id' => $questionId],
                ['text_answer' => $answer, 'selected_option' => null, 'is_correct' => null, 'answered_at' => now()]
            );
        }

        // Çoktan seçmeli ve Doğru/Yanlış: seçenek karşılaştır
        $isCorrect = $question->correct_option === $answer;

        return ExamAnswer::updateOrCreate(
            ['exam_attempt_id' => $attempt->id, 'question_id' => $questionId],
            ['selected_option' => $answer, 'text_answer' => null, 'is_correct' => $isCorrect, 'answered_at' => now()]
        );
    }

    public function finishAttempt(ExamAttempt $attempt): ExamAttempt
    {
        // Yalnızca otomatik puanlanabilen soruları say (MCQ + T/F)
        $autoGradedTotal = $attempt->enrollment->course->questions()
            ->whereIn('question_type', ['multiple_choice', 'true_false'])
            ->count();

        $correctCount = $attempt->answers()->where('is_correct', true)->count();

        // Puan = (doğru / otomatik toplam) * 100; eğer otomatik soru yoksa 0
        $score = $autoGradedTotal > 0 ? (int) round(($correctCount / $autoGradedTotal) * 100) : 0;

        // Açık uçlu cevap var mı? Varsa manuel değerlendirme gerekiyor
        $hasOpenEnded = $attempt->answers()
            ->whereHas('question', fn ($q) => $q->where('question_type', 'open_ended'))
            ->exists();

        $attempt->update([
            'correct_answers'      => $correctCount,
            'score'                => $score,
            'finished_at'          => now(),
            'needs_manual_grading' => $hasOpenEnded,
        ]);

        return $attempt->fresh();
    }

    public function calculateFinalScore(ExamAttempt $attempt): void
    {
        $totalQuestions = $attempt->answers()->count();
        if ($totalQuestions === 0) {
            return;
        }

        // Auto-graded doğru cevaplar (MCQ + T/F)
        $autoCorrect = $attempt->answers()->where('is_correct', true)->count();

        // Açık uçlu sorular: manual_score / 10 → normalize (0-1 arası katkı)
        $openEndedPoints = $attempt->answers()
            ->whereHas('question', fn ($q) => $q->where('question_type', 'open_ended'))
            ->whereNotNull('manual_score')
            ->get()
            ->sum(fn ($a) => $a->manual_score / 10);

        $score    = (int) round((($autoCorrect + $openEndedPoints) / $totalQuestions) * 100);
        $enrollment = $attempt->enrollment;
        $isPassed   = $score >= $enrollment->course->passing_score;

        DB::transaction(function () use ($attempt, $score, $isPassed) {
            $attempt->update([
                'score'                       => $score,
                'is_passed'                   => $isPassed,
                'needs_manual_grading'        => false,
                'manual_grading_completed_at' => now(),
            ]);
        });

        // Post-exam akışı: sertifika, enrollment durumu, bildirim
        if ($attempt->exam_type === 'post_exam') {
            $freshAttempt = $attempt->fresh();
            $user = User::find($enrollment->user_id);
            $user?->notify(new ExamResultNotification($freshAttempt, $enrollment->course->title));
            $this->handlePostExamResult($enrollment, $freshAttempt, $isPassed);
        }
    }
}
