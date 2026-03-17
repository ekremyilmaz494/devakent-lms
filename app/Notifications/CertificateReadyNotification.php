<?php

namespace App\Notifications;

use App\Models\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Certificate $certificate
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $courseName = $this->certificate->course->title ?? 'Eğitim';

        return (new MailMessage)
            ->subject('Sertifikanız Hazır: ' . $courseName)
            ->greeting('Tebrikler ' . ($notifiable->first_name ?? $notifiable->name) . '!')
            ->line("**{$courseName}** eğitimini başarıyla tamamladınız.")
            ->line("Sertifika No: **{$this->certificate->certificate_number}**")
            ->line("Puan: **{$this->certificate->final_score}/100**")
            ->action('Sertifikamı İndir', url('/staff/certificates/' . $this->certificate->id . '/download'))
            ->line('Başarılarınızın devamını dileriz!')
            ->salutation('Devakent LMS');
    }
}
