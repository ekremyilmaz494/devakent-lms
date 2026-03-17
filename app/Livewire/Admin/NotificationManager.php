<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use App\Models\Notification;
use App\Models\NotificationRecipient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationManager extends AdminComponent
{
    use WithPagination;

    // Form fields
    public string $title = '';
    public string $message = '';
    public string $type = 'info';
    public string $target_type = 'all';
    public ?int $target_department_id = null;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'message' => 'required|string|max:500',
            'type' => 'required|in:info,warning,success',
            'target_type' => 'required|in:all,department',
            'target_department_id' => 'required_if:target_type,department|nullable|exists:departments,id',
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required'                   => __('lms.val_notif_title'),
            'title.max'                        => __('lms.val_notif_title_max'),
            'message.required'                 => __('lms.val_notif_message'),
            'message.max'                      => __('lms.val_notif_message_max'),
            'target_department_id.required_if' => __('lms.val_notif_dept'),
        ];
    }

    public function send(): void
    {
        $this->validate();

        // Aynı admin tarafından son 10 saniye içinde aynı başlık + hedef ile gönderim yapılmışsa engelle
        $recentDuplicate = Notification::where('created_by', Auth::id())
            ->where('title', $this->title)
            ->where('target_type', $this->target_type)
            ->where('created_at', '>=', now()->subSeconds(10))
            ->exists();

        if ($recentDuplicate) {
            session()->flash('error', __('lms.notification_duplicate'));
            return;
        }

        $notification = Notification::create([
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'created_by' => Auth::id(),
            'target_type' => $this->target_type,
            'target_department_id' => $this->target_type === 'department' ? $this->target_department_id : null,
        ]);

        // Determine recipients
        $query = User::role('staff')->where('is_active', true);
        if ($this->target_type === 'department' && $this->target_department_id) {
            $query->where('department_id', $this->target_department_id);
        }

        $recipients = $query->pluck('id');

        $rows = $recipients->map(fn ($userId) => [
            'notification_id' => $notification->id,
            'user_id'         => $userId,
            'is_read'         => false,
        ])->all();

        foreach (array_chunk($rows, 500) as $chunk) {
            NotificationRecipient::insert($chunk);
        }

        $this->reset(['title', 'message']);
        $this->type = 'info';
        $this->target_type = 'all';
        $this->target_department_id = null;

        session()->flash('success', __('lms.notification_sent_msg', ['count' => $recipients->count()]));
    }

    public function render()
    {
        $notifications = Notification::with(['creator', 'targetDepartment'])
            ->withCount(['recipients', 'recipients as read_count' => fn ($q) => $q->where('is_read', true)])
            ->orderByDesc('created_at')
            ->paginate(10);

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('livewire.admin.notification-manager', compact('notifications', 'departments'));
    }
}
