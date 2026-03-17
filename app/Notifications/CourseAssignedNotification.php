<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Course $course
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Yeni Eğitim Atandı: ' . $this->course->title)
            ->greeting('Merhaba ' . ($notifiable->first_name ?? $notifiable->name) . ',')
            ->line('Size yeni bir eğitim atanmıştır:')
            ->line('**' . $this->course->title . '**')
            ->line($this->course->description ? \Illuminate\Support\Str::limit($this->course->description, 150) : '')
            ->when($this->course->end_date, function ($mail) {
                $mail->line('Son tarih: **' . $this->course->end_date->format('d.m.Y') . '**');
            })
            ->action('Eğitime Git', url('/staff/courses/' . $this->course->id))
            ->line('Başarılar dileriz!')
            ->salutation('Devakent LMS');
    }
}
