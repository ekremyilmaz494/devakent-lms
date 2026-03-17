<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\NotificationRecipient;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $recipients = NotificationRecipient::where('user_id', $user->id)
            ->with(['notification.creator'])
            ->orderByDesc('notification_id')
            ->get();

        $stats = [
            'total' => $recipients->count(),
            'unread' => $recipients->where('is_read', false)->count(),
        ];

        return view('staff.notifications', compact('recipients', 'stats'));
    }

    public function markAsRead($id)
    {
        $recipient = NotificationRecipient::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $recipient->update(['is_read' => true, 'read_at' => now()]);
        Cache::forget("header.unread_notifs.{$recipient->user_id}");
        Cache::forget("staff.unread_notifications.{$recipient->user_id}");

        return back()->with('success', 'Bildirim okundu olarak işaretlendi.');
    }

    public function markAllAsRead()
    {
        $userId = auth()->id();
        NotificationRecipient::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
        Cache::forget("header.unread_notifs.{$userId}");
        Cache::forget("staff.unread_notifications.{$userId}");

        return back()->with('success', 'Tüm bildirimler okundu olarak işaretlendi.');
    }
}
