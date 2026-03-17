<?php

namespace App\Notifications;

use App\Models\ExamAttempt;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExamResultNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private ExamAttempt $attempt,
        private string $courseName
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->attempt->is_passed ? 'Başarılı' : 'Başarısız';
        $emoji = $this->attempt->is_passed ? '✅' : '❌';

        $mail = (new MailMessage)
            ->subject("{$emoji} Sınav Sonucu: {$this->courseName}")
            ->greeting('Merhaba ' . ($notifiable->first_name ?? $notifiable->name) . ',')
            ->line("**{$this->courseName}** eğitiminin son sınav sonucunuz:")
            ->line("Puan: **{$this->attempt->score}/100** — {$status}")
            ->line("Doğru cevap: {$this->attempt->correct_answers}/{$this->attempt->total_questions}");

        if ($this->attempt->is_passed) {
            $mail->line('Tebrikler! Eğitimi başarıyla tamamladınız. Sertifikanız hazırlanıyor.')
                 ->action('Sertifikalarım', url('/staff/certificates'));
        } else {
            $mail->line('Eğitimi tekrar alarak sınavı yeniden verebilirsiniz.')
                 ->action('Eğitime Dön', url('/staff/courses'));
        }

        return $mail->salutation('Devakent LMS');
    }
}
