<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationManager extends Component
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

    protected $messages = [
        'title.required' => 'Başlık zorunludur.',
        'title.max' => 'Başlık en fazla 100 karakter olabilir.',
        'message.required' => 'Mesaj zorunludur.',
        'message.max' => 'Mesaj en fazla 500 karakter olabilir.',
        'target_department_id.required_if' => 'Departman seçimi zorunludur.',
    ];

    public function send(): void
    {
        $this->validate();

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

        foreach ($recipients as $userId) {
            $notification->recipients()->create([
                'user_id' => $userId,
                'is_read' => false,
            ]);
        }

        $this->reset(['title', 'message']);
        $this->type = 'info';
        $this->target_type = 'all';
        $this->target_department_id = null;

        session()->flash('success', $recipients->count() . ' kişiye bildirim gönderildi.');
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
