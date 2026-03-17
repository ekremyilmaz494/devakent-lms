<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\ExamAttempt;
use App\Models\Certificate;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // İstatistik kartları — 5 dk cache
        $stats = Cache::remember('dashboard.stats', 300, function () {
            $totalEnrollments = Enrollment::count();
            $completedEnrollments = Enrollment::where('status', 'completed')->count();
            return [
                'totalStaff' => User::role('staff')->count(),
                'activeCourses' => Course::where('status', 'published')->count(),
                'totalEnrollments' => $totalEnrollments,
                'completedEnrollments' => $completedEnrollments,
                'completionRate' => $totalEnrollments > 0 ? round($completedEnrollments / $totalEnrollments * 100, 1) : 0,
                'certificatesIssued' => Certificate::count(),
            ];
        });
        extract($stats);

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

        // Department stats (sidebar)
        $departmentStats = Department::where('is_active', true)
            ->withCount('users')
            ->orderByDesc('users_count')
            ->take(5)
            ->get();

        // ── YENİ: Dikkat Gerektiren Personeller ──
        // 30+ gün giriş yapmayan aktif personel
        $inactiveStaff = User::role('staff')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('last_login_at')
                  ->orWhere('last_login_at', '<', now()->subDays(30));
            })
            ->with('department')
            ->orderBy('last_login_at')
            ->take(5)
            ->get();

        // Zorunlu eğitimi tamamlamamış personel
        $mandatoryIncomplete = User::role('staff')
            ->where('is_active', true)
            ->whereHas('enrollments', function ($q) {
                $q->whereHas('course', fn ($c) => $c->where('is_mandatory', true))
                  ->whereIn('status', ['not_started', 'in_progress', 'failed']);
            })
            ->with(['department', 'enrollments' => function ($q) {
                $q->whereHas('course', fn ($c) => $c->where('is_mandatory', true))
                  ->whereIn('status', ['not_started', 'in_progress', 'failed'])
                  ->with('course');
            }])
            ->take(5)
            ->get();

        // ── YENİ: Süresi Yaklaşan Eğitimler ──
        $expiringCourses = Course::where('status', 'published')
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [now(), now()->addDays(14)])
            ->withCount([
                'enrollments',
                'enrollments as completed_count' => fn ($q) => $q->where('status', 'completed'),
            ])
            ->orderBy('end_date')
            ->take(5)
            ->get();

        // ── YENİ: Departman Bazlı Tamamlanma Oranı ──
        $deptCompletionRates = Department::where('is_active', true)
            ->withCount('users')
            ->get()
            ->map(function ($dept) {
                $userIds = $dept->users()->pluck('users.id');
                $total = Enrollment::whereIn('user_id', $userIds)->count();
                $completed = Enrollment::whereIn('user_id', $userIds)->where('status', 'completed')->count();
                $dept->completion_rate = $total > 0 ? round($completed / $total * 100, 1) : 0;
                $dept->total_enrollments = $total;
                $dept->completed_enrollments = $completed;
                return $dept;
            })
            ->sortByDesc('completion_rate')
            ->values();

        // ── YENİ: Sınav İstatistikleri ──
        $totalExams = ExamAttempt::count();
        $passedExams = ExamAttempt::where('is_passed', true)->count();
        $avgScore = round(ExamAttempt::avg('score') ?? 0, 1);
        $examPassRate = $totalExams > 0 ? round($passedExams / $totalExams * 100, 1) : 0;

        $recentExams = ExamAttempt::with(['enrollment.user', 'enrollment.course'])
            ->latest()
            ->take(5)
            ->get();

        // ── YENİ: Haftalık Aktivite Trendi ──
        $weeklyActivity = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);
            return [
                'date' => $date->format('d.m'),
                'day' => $date->translatedFormat('D'),
                'enrollments' => Enrollment::whereDate('created_at', $date->toDateString())->count(),
                'exams' => ExamAttempt::whereDate('created_at', $date->toDateString())->count(),
            ];
        });

        // ── YENİ: Aylık Tamamlanma Trendi (son 6 ay) ──
        $monthlyTrend = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();
            return [
                'month' => $date->translatedFormat('M Y'),
                'completions' => Enrollment::where('status', 'completed')
                    ->whereBetween('updated_at', [$start, $end])->count(),
                'new_enrollments' => Enrollment::whereBetween('created_at', [$start, $end])->count(),
                'exams_passed' => ExamAttempt::where('is_passed', true)
                    ->whereBetween('created_at', [$start, $end])->count(),
            ];
        });

        // ── YENİ: Kayıt Durum Dağılımı (doughnut chart) ──
        $enrollmentStatusDist = [
            'not_started' => Enrollment::where('status', 'not_started')->count(),
            'in_progress' => Enrollment::where('status', 'in_progress')->count(),
            'completed' => Enrollment::where('status', 'completed')->count(),
            'failed' => Enrollment::where('status', 'failed')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalStaff', 'activeCourses', 'completionRate', 'certificatesIssued',
            'totalEnrollments', 'completedEnrollments',
            'recentEnrollments', 'topCourses', 'departmentStats',
            'inactiveStaff', 'mandatoryIncomplete', 'expiringCourses',
            'deptCompletionRates', 'totalExams', 'passedExams', 'avgScore',
            'examPassRate', 'recentExams', 'weeklyActivity',
            'monthlyTrend', 'enrollmentStatusDist'
        ));
    }
}
