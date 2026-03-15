<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\ExamAttempt;
use App\Models\Certificate;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStaff = User::role('staff')->count();
        $activeCourses = Course::where('status', 'published')->count();
        $totalEnrollments = Enrollment::count();
        $completedEnrollments = Enrollment::where('status', 'completed')->count();
        $completionRate = $totalEnrollments > 0 ? round($completedEnrollments / $totalEnrollments * 100, 1) : 0;
        $certificatesIssued = Certificate::count();

        // Recent enrollments for the table
        $recentEnrollments = Enrollment::with(['user', 'course.category'])
            ->latest()
            ->take(8)
            ->get();

        // Top performing courses
        $topCourses = Course::withCount(['enrollments', 'questions'])
            ->with('category')
            ->where('status', 'published')
            ->orderByDesc('enrollments_count')
            ->take(6)
            ->get();

        // Department stats
        $departmentStats = Department::where('is_active', true)
            ->withCount('users')
            ->orderByDesc('users_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalStaff', 'activeCourses', 'completionRate', 'certificatesIssued',
            'totalEnrollments', 'completedEnrollments',
            'recentEnrollments', 'topCourses', 'departmentStats'
        ));
    }
}
