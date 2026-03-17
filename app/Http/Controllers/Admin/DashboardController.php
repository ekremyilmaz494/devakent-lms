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
use Illuminate\Support\Facades\DB;

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

        // Süresi Yaklaşan Eğitimler
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

        // ── Departman Bazlı Tamamlanma Oranı ───────────────────────────────────
        // Eskisi: N+1 (her departman için 2 ayrı COUNT sorgusu)
        // Yenisi: tek JOIN sorgusu + 10 dk cache
        $deptCompletionRates = Cache::remember('dashboard.dept_completion', 600, function () {
            $enrollStats = DB::table('enrollments')
                ->join('users', 'enrollments.user_id', '=', 'users.id')
                ->whereNull('users.deleted_at')
                ->groupBy('users.department_id')
                ->select(
                    'users.department_id',
                    DB::raw('COUNT(*) as total_enrollments'),
                    DB::raw("SUM(CASE WHEN enrollments.status = 'completed' THEN 1 ELSE 0 END) as completed_enrollments")
                )
                ->get()
                ->keyBy('department_id');

            return Department::where('is_active', true)
                ->withCount('users')
                ->get()
                ->map(function ($dept) use ($enrollStats) {
                    $s = $enrollStats->get($dept->id);
                    $total     = $s?->total_enrollments     ?? 0;
                    $completed = $s?->completed_enrollments ?? 0;
                    $dept->completion_rate       = $total > 0 ? round($completed / $total * 100, 1) : 0;
                    $dept->total_enrollments     = $total;
                    $dept->completed_enrollments = $completed;
                    return $dept;
                })
                ->sortByDesc('completion_rate')
                ->values();
        });

        // ── Sınav İstatistikleri ───────────────────────────────────────────────
        // Eskisi: 3 ayrı sorgu, cache yok
        // Yenisi: cache'li
        [$totalExams, $passedExams, $avgScore, $examPassRate] = Cache::remember('dashboard.exam_stats', 600, function () {
            $total  = ExamAttempt::count();
            $passed = ExamAttempt::where('is_passed', true)->count();
            $avg    = round(ExamAttempt::avg('score') ?? 0, 1);
            return [$total, $passed, $avg, $total > 0 ? round($passed / $total * 100, 1) : 0];
        });

        $recentExams = ExamAttempt::with(['enrollment.user', 'enrollment.course'])
            ->latest()
            ->take(5)
            ->get();

        // ── Haftalık Aktivite Trendi ───────────────────────────────────────────
        // Eskisi: 14 ayrı COUNT sorgusu (7 gün × 2 model)
        // Yenisi: 2 GROUP BY sorgusu + 30 dk cache
        $weeklyActivity = Cache::remember('dashboard.weekly_activity', 1800, function () {
            $startDate = now()->subDays(6)->startOfDay();

            $enrollsByDay = Enrollment::where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $examsByDay = ExamAttempt::where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            return collect(range(6, 0))->map(function ($daysAgo) use ($enrollsByDay, $examsByDay) {
                $date = now()->subDays($daysAgo);
                $key  = $date->toDateString();
                return [
                    'date'        => $date->format('d.m'),
                    'day'         => $date->translatedFormat('D'),
                    'enrollments' => $enrollsByDay->get($key, 0),
                    'exams'       => $examsByDay->get($key, 0),
                ];
            });
        });

        // ── Aylık Tamamlanma Trendi ────────────────────────────────────────────
        // Eskisi: 18 ayrı COUNT sorgusu (6 ay × 3 model)
        // Yenisi: 3 GROUP BY sorgusu + 1 saat cache
        $monthlyTrend = Cache::remember('dashboard.monthly_trend', 3600, function () {
            $from = now()->subMonths(5)->startOfMonth();

            $completionsByMonth = Enrollment::where('status', 'completed')
                ->where('updated_at', '>=', $from)
                ->selectRaw("DATE_FORMAT(updated_at, '%Y-%m') as month, COUNT(*) as count")
                ->groupBy('month')
                ->pluck('count', 'month');

            $enrollsByMonth = Enrollment::where('created_at', '>=', $from)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
                ->groupBy('month')
                ->pluck('count', 'month');

            $passedByMonth = ExamAttempt::where('is_passed', true)
                ->where('created_at', '>=', $from)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
                ->groupBy('month')
                ->pluck('count', 'month');

            return collect(range(5, 0))->map(function ($monthsAgo) use ($completionsByMonth, $enrollsByMonth, $passedByMonth) {
                $date = now()->subMonths($monthsAgo);
                $key  = $date->format('Y-m');
                return [
                    'month'           => $date->translatedFormat('M Y'),
                    'completions'     => $completionsByMonth->get($key, 0),
                    'new_enrollments' => $enrollsByMonth->get($key, 0),
                    'exams_passed'    => $passedByMonth->get($key, 0),
                ];
            });
        });

        // ── Kayıt Durum Dağılımı ───────────────────────────────────────────────
        // Eskisi: 4 ayrı COUNT sorgusu
        // Yenisi: tek GROUP BY sorgusu
        $statusCounts = Enrollment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        $enrollmentStatusDist = [
            'not_started' => $statusCounts->get('not_started', 0),
            'in_progress' => $statusCounts->get('in_progress', 0),
            'completed'   => $statusCounts->get('completed',   0),
            'failed'      => $statusCounts->get('failed',      0),
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
