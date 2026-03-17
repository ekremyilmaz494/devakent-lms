<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\User;
use App\Notifications\CourseAssignedNotification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CourseEnrollment extends AdminComponent
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
            session()->flash('error', __('lms.no_staff_selected'));
            return;
        }

        $course = Course::findOrFail($this->courseId);

        if ($course->status !== 'published') {
            session()->flash('error', __('lms.course_not_published'));
            return;
        }

        // Eskisi: N+1 (her kullanıcı için ayrı EXISTS + ayrı find sorgusu)
        // Yenisi: tek sorguda mevcut kayıtları toplu kontrol
        $existingUserIds = Enrollment::where('course_id', $this->courseId)
            ->whereIn('user_id', $this->selectedUsers)
            ->pluck('user_id')
            ->flip()
            ->all();

        $toEnroll = array_filter($this->selectedUsers, fn ($id) => !isset($existingUserIds[$id]));
        $skipped  = count($this->selectedUsers) - count($toEnroll);
        $enrolled = 0;

        $usersToNotify = User::whereIn('id', $toEnroll)->where('is_active', true)->get()->keyBy('id');

        // Pasif kullanıcıları toEnroll listesinden çıkar
        $toEnroll = array_filter($toEnroll, fn ($id) => $usersToNotify->has($id));

        DB::transaction(function () use ($toEnroll, $course, $usersToNotify, &$enrolled) {
            foreach ($toEnroll as $userId) {
                Enrollment::create([
                    'user_id'         => $userId,
                    'course_id'       => $this->courseId,
                    'status'          => 'not_started',
                    'current_attempt' => 1,
                ]);

                $usersToNotify->get($userId)?->notify(new CourseAssignedNotification($course));
                $enrolled++;
            }
        });

        $message = __('lms.enrollment_added', ['count' => $enrolled]);
        if ($skipped > 0) {
            $message .= ' ' . __('lms.enrollment_already_enrolled', ['count' => $skipped]);
        }

        session()->flash('success', $message);
        $this->showModal = false;
        $this->selectedUsers = [];
    }

    public function enrollDepartment(int $departmentId): void
    {
        $course = Course::findOrFail($this->courseId);

        if ($course->status !== 'published') {
            session()->flash('error', __('lms.course_not_published'));
            return;
        }
        $users = User::role('staff')
            ->where('department_id', $departmentId)
            ->where('is_active', true)
            ->get();

        $userIds = $users->pluck('id')->all();

        // Eskisi: N+1 (her kullanıcı için ayrı EXISTS sorgusu)
        // Yenisi: tek sorguda toplu kontrol
        $existingIds = Enrollment::where('course_id', $this->courseId)
            ->whereIn('user_id', $userIds)
            ->pluck('user_id')
            ->flip()
            ->all();

        $enrolled = 0;
        DB::transaction(function () use ($users, $existingIds, $course, &$enrolled) {
            foreach ($users as $user) {
                if (isset($existingIds[$user->id])) {
                    continue;
                }
                $enrollment = Enrollment::firstOrCreate(
                    ['user_id' => $user->id, 'course_id' => $this->courseId],
                    ['status' => 'not_started', 'current_attempt' => 1]
                );
                if ($enrollment->wasRecentlyCreated) {
                    $user->notify(new CourseAssignedNotification($course));
                    $enrolled++;
                }
            }
        });

        session()->flash('success', __('lms.enrollment_added', ['count' => $enrolled]));
    }

    public function removeEnrollment(int $enrollmentId): void
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);

        if ($enrollment->status !== 'not_started') {
            session()->flash('error', __('lms.enrollment_started_cannot_remove'));
            return;
        }

        $enrollment->delete();
        session()->flash('success', __('lms.enrollment_removed'));
    }

    #[Computed]
    public function course(): Course
    {
        return Course::withCount('enrollments')->findOrFail($this->courseId);
    }

    #[Computed]
    public function enrolledUsers()
    {
        return Enrollment::with(['user.department'])
            ->where('course_id', $this->courseId)
            ->get();
    }

    public function render()
    {
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
            'course' => $this->course,
            'enrolledUsers' => $this->enrolledUsers,
            'availableUsers' => $availableUsers,
            'departments' => $departments,
        ]);
    }
}
