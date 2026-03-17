<?php

namespace App\Livewire\Admin;

use App\Exports\ReportExport;
use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\ExamAttempt;
use App\Models\SystemSetting;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

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

    public function exportExcel()
    {
        $prepared = $this->prepareExportData();

        return Excel::download(
            new ReportExport($prepared['rows'], $prepared['headings'], $prepared['title']),
            'rapor-' . $this->activeTab . '-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf()
    {
        $prepared = $this->prepareExportData();

        $pdf = Pdf::loadView('pdf.report', [
            'title' => $prepared['title'],
            'headings' => $prepared['headings'],
            'rows' => $prepared['rows'],
            'institution' => SystemSetting::get('institution_name', 'Devakent Hastanesi'),
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'rapor-' . $this->activeTab . '-' . date('Y-m-d') . '.pdf'
        );
    }

    private function prepareExportData(): array
    {
        $data = match ($this->activeTab) {
            'course' => $this->getCourseReport(),
            'department' => $this->getDepartmentReport(),
            'staff' => $this->getStaffReport(),
            'time' => $this->getTimeReport(),
            default => $this->getCourseReport(),
        };

        $reportData = $data['reportData'];

        return match ($this->activeTab) {
            'course' => [
                'title' => 'Eğitim Bazlı Rapor',
                'headings' => ['Eğitim', 'Kategori', 'Kayıt', 'Tamamlanan', 'Tamamlanma %', 'Ön Sınav Ort.', 'Son Sınav Ort.'],
                'rows' => $reportData->map(fn ($r) => [
                    $r['title'], $r['category'], $r['enrollments'], $r['completed'],
                    '%' . $r['completion_rate'], $r['pre_exam_avg'], $r['post_exam_avg'],
                ])->toArray(),
            ],
            'department' => [
                'title' => 'Departman Bazlı Rapor',
                'headings' => ['Departman', 'Personel', 'Kayıt', 'Tamamlanan', 'Tamamlanma %', 'Ön Sınav Ort.', 'Son Sınav Ort.'],
                'rows' => $reportData->map(fn ($r) => [
                    $r['name'], $r['staff_count'], $r['enrollments'], $r['completed'],
                    '%' . $r['completion_rate'], $r['pre_exam_avg'], $r['post_exam_avg'],
                ])->toArray(),
            ],
            'staff' => [
                'title' => 'Personel Bazlı Rapor',
                'headings' => ['Personel', 'Departman', 'Kayıt', 'Tamamlanan', 'Tamamlanma %', 'Ön Sınav Ort.', 'Son Sınav Ort.', 'Sertifika', 'Son Giriş'],
                'rows' => $reportData->map(fn ($r) => [
                    $r['name'], $r['department'], $r['enrollments'], $r['completed'],
                    '%' . $r['completion_rate'], $r['pre_exam_avg'], $r['post_exam_avg'], $r['certificates'], $r['last_login'],
                ])->toArray(),
            ],
            'time' => [
                'title' => 'Zaman Bazlı Rapor (Son 6 Ay)',
                'headings' => ['Ay', 'Toplam Kayıt', 'Tamamlanan', 'Tamamlanma %'],
                'rows' => $reportData->map(fn ($r) => [
                    $r['month'], $r['total'], $r['completed'], '%' . $r['completion_rate'],
                ])->toArray(),
            ],
        };
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
                    ->where('exam_type', 'pre_exam')
                    ->avg('score');

                $postExamAvg = ExamAttempt::whereHas('enrollment', fn ($q) => $q->where('course_id', $course->id))
                    ->where('exam_type', 'post_exam')
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

                $preExamAvg = ExamAttempt::whereHas('enrollment', fn ($q) => $q->whereIn('user_id', $userIds))
                    ->where('exam_type', 'pre_exam')
                    ->whereNotNull('finished_at')
                    ->avg('score');

                $postExamAvg = ExamAttempt::whereHas('enrollment', fn ($q) => $q->whereIn('user_id', $userIds))
                    ->where('exam_type', 'post_exam')
                    ->whereNotNull('finished_at')
                    ->avg('score');

                return [
                    'name' => $dept->name,
                    'staff_count' => $dept->users_count,
                    'enrollments' => $totalEnrollments,
                    'completed' => $completedEnrollments,
                    'completion_rate' => $completionRate,
                    'pre_exam_avg' => round($preExamAvg ?? 0, 1),
                    'post_exam_avg' => round($postExamAvg ?? 0, 1),
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

                $preExamAvg = ExamAttempt::whereHas('enrollment', fn ($q) => $q->where('user_id', $user->id))
                    ->where('exam_type', 'pre_exam')
                    ->whereNotNull('finished_at')
                    ->avg('score');

                $postExamAvg = ExamAttempt::whereHas('enrollment', fn ($q) => $q->where('user_id', $user->id))
                    ->where('exam_type', 'post_exam')
                    ->whereNotNull('finished_at')
                    ->avg('score');

                return [
                    'name' => $user->full_name,
                    'department' => $user->department?->name ?? '—',
                    'enrollments' => $user->enrollments_count,
                    'completed' => $user->completed_count,
                    'completion_rate' => $completionRate,
                    'pre_exam_avg' => round($preExamAvg ?? 0, 1),
                    'post_exam_avg' => round($postExamAvg ?? 0, 1),
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
