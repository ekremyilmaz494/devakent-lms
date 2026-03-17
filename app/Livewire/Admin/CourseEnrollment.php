<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\User;
use App\Notifications\CourseAssignedNotification;
use Livewire\Component;

class CourseEnrollment extends Component
{
    public int $courseId;
    public string $search = '';
    public string $filterDepartment = '';
    public array $selectedUsers = [];
    public bool $showModal = false;

    public function mount(int $courseId): void
    {
        $this->courseId = $courseId;
    }

    public function openModal(): void
    {
        $this->selectedUsers = [];
        $this->search = '';
        $this->filterDepartment = '';
        $this->showModal = true;
    }

    public function enrollSelected(): void
    {
        if (empty($this->selectedUsers)) {
            session()->flash('error', 'Lütfen en az bir personel seçin.');
            return;
        }

        $course = Course::findOrFail($this->courseId);
        $enrolled = 0;
        $skipped = 0;

        foreach ($this->selectedUsers as $userId) {
            // Zaten kayıtlı mı?
            $exists = Enrollment::where('user_id', $userId)
                ->where('course_id', $this->courseId)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Enrollment::create([
                'user_id' => $userId,
                'course_id' => $this->courseId,
                'status' => 'not_started',
                'current_attempt' => 1,
            ]);

            // E-posta bildirimi
            $user = User::find($userId);
            $user?->notify(new CourseAssignedNotification($course));

            $enrolled++;
        }

        $message = "{$enrolled} personel eğitime atandı.";
        if ($skipped > 0) {
            $message .= " {$skipped} personel zaten kayıtlı.";
        }

        session()->flash('success', $message);
        $this->showModal = false;
        $this->selectedUsers = [];
    }

    public function enrollDepartment(int $departmentId): void
    {
        $course = Course::findOrFail($this->courseId);
        $users = User::role('staff')
            ->where('department_id', $departmentId)
            ->where('is_active', true)
            ->get();

        $enrolled = 0;
        foreach ($users as $user) {
            $exists = Enrollment::where('user_id', $user->id)
                ->where('course_id', $this->courseId)
                ->exists();

            if (!$exists) {
                Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $this->courseId,
                    'status' => 'not_started',
                    'current_attempt' => 1,
                ]);
                $user->notify(new CourseAssignedNotification($course));
                $enrolled++;
            }
        }

        session()->flash('success', "{$enrolled} personel eğitime atandı.");
    }

    public function removeEnrollment(int $enrollmentId): void
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);

        if ($enrollment->status !== 'not_started') {
            session()->flash('error', 'Başlamış bir eğitim kaydı kaldırılamaz.');
            return;
        }

        $enrollment->delete();
        session()->flash('success', 'Kayıt kaldırıldı.');
    }

    public function render()
    {
        $course = Course::with(['enrollments.user.department'])->findOrFail($this->courseId);

        $availableUsers = User::role('staff')
            ->where('is_active', true)
            ->whereDoesntHave('enrollments', fn ($q) => $q->where('course_id', $this->courseId))
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('first_name', 'like', "%{$this->search}%")
                       ->orWhere('last_name', 'like', "%{$this->search}%")
                       ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterDepartment, fn ($q) => $q->where('department_id', $this->filterDepartment))
            ->with('department')
            ->orderBy('first_name')
            ->get();

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('livewire.admin.course-enrollment', [
            'course' => $course,
            'availableUsers' => $availableUsers,
            'departments' => $departments,
        ]);
    }
}
