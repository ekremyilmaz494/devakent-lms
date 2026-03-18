<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminBroadcastNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private string $title,
        private string $message,
        private string $type = 'info'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $emoji = match ($this->type) {
            'warning' => '⚠️',
            'success' => '✅',
            default   => 'ℹ️',
        };

        return (new MailMessage)
            ->subject("{$emoji} {$this->title}")
            ->greeting('Merhaba ' . ($notifiable->first_name ?? $notifiable->name) . ',')
            ->line($this->message)
            ->action('Bildirimlere Git', url('/staff/notifications'))
            ->salutation('Devakent LMS');
    }
}
