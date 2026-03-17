<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function sendToUser(int $userId, string $title, string $message, string $type = 'info'): Notification
    {
        $notification = Notification::create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'target_type' => 'individual',
        ]);

        $notification->recipients()->create([
            'user_id' => $userId,
            'is_read' => false,
        ]);

        return $notification;
    }

    public function sendToDepartment(int $departmentId, string $title, string $message, string $type = 'info'): Notification
    {
        $notification = Notification::create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'target_type' => 'department',
            'target_department_id' => $departmentId,
        ]);

        $userIds = User::where('department_id', $departmentId)
            ->where('is_active', true)
            ->pluck('id');

        foreach ($userIds as $userId) {
            $notification->recipients()->create([
                'user_id' => $userId,
                'is_read' => false,
            ]);
        }

        return $notification;
    }

    public function sendToAll(string $title, string $message, string $type = 'info'): Notification
    {
        $notification = Notification::create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'target_type' => 'all',
        ]);

        $userIds = User::role('staff')->where('is_active', true)->pluck('id');

        foreach ($userIds as $userId) {
            $notification->recipients()->create([
                'user_id' => $userId,
                'is_read' => false,
            ]);
        }

        return $notification;
    }
}
