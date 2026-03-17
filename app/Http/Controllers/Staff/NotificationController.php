<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\NotificationRecipient;

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

        return back()->with('success', 'Bildirim okundu olarak işaretlendi.');
    }

    public function markAllAsRead()
    {
        NotificationRecipient::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', 'Tüm bildirimler okundu olarak işaretlendi.');
    }
}
