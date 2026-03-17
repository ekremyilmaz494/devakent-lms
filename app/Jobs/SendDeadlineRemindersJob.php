<?php

namespace App\Jobs;

use App\Models\Enrollment;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDeadlineRemindersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(NotificationService $service): void
    {
        $threeDaysFromNow = now()->addDays(3)->toDateString();

        $enrollments = Enrollment::whereIn('status', ['not_started', 'in_progress'])
            ->whereHas('course', fn ($q) => $q->whereDate('end_date', $threeDaysFromNow))
            ->with('course')
            ->get();

        foreach ($enrollments as $enrollment) {
            $service->sendToUser(
                $enrollment->user_id,
                'Son Tarih Yaklaşıyor!',
                '"' . $enrollment->course->title . '" eğitiminin son tarihi 3 gün sonra (' . $enrollment->course->end_date->format('d.m.Y') . '). Lütfen eğitimi tamamlayın.',
                'warning'
            );
        }
    }
}
