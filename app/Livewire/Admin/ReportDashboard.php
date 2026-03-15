<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReportDashboard extends Component
{
    public string $activeTab = 'course';

    public function render()
    {
        $data = match ($this->activeTab) {
            'course' => $this->getCourseReport(),
            'department' => $this->getDepartmentReport(),
            'staff' => $this->getStaffReport(),
            'time' => $this->getTimeReport(),
            default => $this->getCourseReport(),
        };

        // Stats cards (always visible)
        $stats = [
            'totalEnrollments' => Enrollment::count(),
            'completedEnrollments' => Enrollment::where('status', 'completed')->count(),
            'avgScore' => round(ExamAttempt::where('is_passed', true)->avg('score') ?? 0, 1),
            'activeCourses' => Course::where('status', 'published')->count(),
        ];

        return view('livewire.admin.report-dashboard', array_merge($data, ['stats' => $stats]));
    }

    private function getCourseReport(): array
    {
        $courses = Course::where('status', 'published')
            ->with('category')
            ->withCount(['enrollments', 'enrollments as completed_count' => function ($q) {
                $q->where('status', 'completed');
            }, 'questions'])
            ->get()
            ->map(function ($course) {
                $completionRate = $course->enrollments_count > 0
                    ? round($course->completed_count / $course->enrollments_count * 100, 1)
                    : 0;

                $preExamAvg = ExamAttempt::whereHas('enrollment', fn ($q) => $q->where('course_id', $course->id))
                    ->where('exam_type', 'pre')
                    ->avg('score');

                $postExamAvg = ExamAttempt::whereHas('enrollment', fn ($q) => $q->where('course_id', $course->id))
                    ->where('exam_type', 'post')
                    ->avg('score');

                return [
                    'title' => $course->title,
                    'category' => $course->category?->name ?? '—',
                    'category_color' => $course->category?->color ?? '#6b7280',
                    'enrollments' => $course->enrollments_count,
                    'completed' => $course->completed_count,
                    'completion_rate' => $completionRate,
                    'pre_exam_avg' => round($preExamAvg ?? 0, 1),
                    'post_exam_avg' => round($postExamAvg ?? 0, 1),
                    'questions' => $course->questions_count,
                ];
            });

        return ['reportData' => $courses];
    }

    private function getDepartmentReport(): array
    {
        $departments = Department::where('is_active', true)
            ->withCount('users')
            ->get()
            ->map(function ($dept) {
                $userIds = $dept->users()->pluck('users.id');
                $totalEnrollments = Enrollment::whereIn('user_id', $userIds)->count();
                $completedEnrollments = Enrollment::whereIn('user_id', $userIds)->where('status', 'completed')->count();
                $completionRate = $totalEnrollments > 0 ? round($completedEnrollments / $totalEnrollments * 100, 1) : 0;

                $avgScore = ExamAttempt::whereHas('enrollment', fn ($q) => $q->whereIn('user_id', $userIds))
                    ->where('is_passed', true)
                    ->avg('score');

                return [
                    'name' => $dept->name,
                    'staff_count' => $dept->users_count,
                    'enrollments' => $totalEnrollments,
                    'completed' => $completedEnrollments,
                    'completion_rate' => $completionRate,
                    'avg_score' => round($avgScore ?? 0, 1),
                ];
            });

        return ['reportData' => $departments];
    }

    private function getStaffReport(): array
    {
        $staff = User::role('staff')
            ->with('department')
            ->withCount([
                'enrollments',
                'enrollments as completed_count' => fn ($q) => $q->where('status', 'completed'),
                'certificates',
            ])
            ->orderBy('first_name')
            ->get()
            ->map(function ($user) {
                $completionRate = $user->enrollments_count > 0
                    ? round($user->completed_count / $user->enrollments_count * 100, 1)
                    : 0;

                $avgScore = ExamAttempt::whereHas('enrollment', fn ($q) => $q->where('user_id', $user->id))
                    ->where('is_passed', true)
                    ->avg('score');

                return [
                    'name' => $user->full_name,
                    'department' => $user->department?->name ?? '—',
                    'enrollments' => $user->enrollments_count,
                    'completed' => $user->completed_count,
                    'completion_rate' => $completionRate,
                    'avg_score' => round($avgScore ?? 0, 1),
                    'certificates' => $user->certificates_count,
                    'last_login' => $user->last_login_at?->diffForHumans() ?? 'Henüz giriş yok',
                ];
            });

        return ['reportData' => $staff];
    }

    private function getTimeReport(): array
    {
        $monthly = Enrollment::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"),
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($row) {
                $rate = $row->total > 0 ? round($row->completed / $row->total * 100, 1) : 0;
                return [
                    'month' => $row->month,
                    'total' => $row->total,
                    'completed' => $row->completed,
                    'completion_rate' => $rate,
                ];
            });

        return ['reportData' => $monthly];
    }
}
