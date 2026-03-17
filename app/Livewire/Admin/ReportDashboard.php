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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReportDashboard extends AdminComponent
{
    public string $activeTab   = 'course';
    public bool   $showFilters = false;

    // ── Filters ───────────────────────────────────────────────────────────────
    public string $dateFrom         = '';
    public string $dateTo           = '';
    public string $filterDepartment = '';
    public string $filterStatus     = '';
    public string $filterMandatory  = '';
    public string $search           = '';
    public string $timeGrouping     = 'monthly';

    // ── Sorting ───────────────────────────────────────────────────────────────
    public string $sortField     = 'completion_rate';
    public string $sortDirection = 'desc';

    public function placeholder(): \Illuminate\View\View
    {
        return view('livewire.placeholders.skeleton');
    }

    public function updatedActiveTab(): void
    {
        $this->sortField     = 'completion_rate';
        $this->sortDirection = 'desc';
        $this->search        = '';
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField     = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function resetFilters(): void
    {
        $this->dateFrom         = '';
        $this->dateTo           = '';
        $this->filterDepartment = '';
        $this->filterStatus     = '';
        $this->filterMandatory  = '';
        $this->search           = '';
    }

    public function removeFilter(string $key): void
    {
        $this->{$key} = '';
    }

    public function render()
    {
        $departments   = Cache::remember('departments.all', now()->addHours(6),
            fn () => Department::where('is_active', true)->orderBy('name')->get()
        );
        $data          = match ($this->activeTab) {
            'course'     => $this->getCourseReport(),
            'department' => $this->getDepartmentReport(),
            'staff'      => $this->getStaffReport(),
            'time'       => $this->getTimeReport(),
            default      => $this->getCourseReport(),
        };
        $stats         = $this->getStats();
        $activeFilters = $this->computeActiveFilters($departments);

        return view('livewire.admin.report-dashboard', array_merge($data, [
            'stats'         => $stats,
            'departments'   => $departments,
            'activeFilters' => $activeFilters,
        ]));
    }

    // ── Active filter chips ───────────────────────────────────────────────────
    private function computeActiveFilters($departments): array
    {
        $filters = [];
        if ($this->dateFrom) {
            $filters[] = ['key' => 'dateFrom',         'label' => 'Baş: ' . $this->dateFrom];
        }
        if ($this->dateTo) {
            $filters[] = ['key' => 'dateTo',           'label' => 'Bit: ' . $this->dateTo];
        }
        if ($this->filterDepartment) {
            $name      = $departments->firstWhere('id', (int) $this->filterDepartment)?->name ?? '—';
            $filters[] = ['key' => 'filterDepartment', 'label' => $name];
        }
        if ($this->filterStatus) {
            $map       = [
                'completed'   => __('lms.completed'),
                'in_progress' => __('lms.in_progress'),
                'not_started' => __('lms.not_started'),
            ];
            $filters[] = ['key' => 'filterStatus',     'label' => $map[$this->filterStatus] ?? $this->filterStatus];
        }
        if ($this->filterMandatory !== '') {
            $filters[] = ['key' => 'filterMandatory',  'label' => $this->filterMandatory === '1' ? __('lms.mandatory_label') : __('lms.optional_label')];
        }
        if ($this->search) {
            $filters[] = ['key' => 'search',           'label' => '"' . $this->search . '"'];
        }
        return $filters;
    }

    // ── Stats (filter-aware) ──────────────────────────────────────────────────
    private function getStats(): array
    {
        $enrollBase = Enrollment::query()
            ->when($this->dateFrom,         fn ($q) => $q->where('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,           fn ($q) => $q->where('created_at', '<=', $this->dateTo . ' 23:59:59'))
            ->when($this->filterDepartment, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('department_id', $this->filterDepartment)))
            ->when($this->filterStatus,     fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterMandatory !== '', fn ($q) => $q->whereHas('course', fn ($c) => $c->where('is_mandatory', $this->filterMandatory === '1')));

        $total     = $enrollBase->count();
        $completed = (clone $enrollBase)->where('status', 'completed')->count();

        $avgScore = ExamAttempt::query()
            ->where('exam_type', 'post_exam')
            ->whereNotNull('finished_at')
            ->when($this->dateFrom,         fn ($q) => $q->where('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,           fn ($q) => $q->where('created_at', '<=', $this->dateTo . ' 23:59:59'))
            ->when($this->filterDepartment, fn ($q) => $q->whereHas('enrollment.user', fn ($u) => $u->where('department_id', $this->filterDepartment)))
            ->avg('score');

        $activeCourses = Course::where('status', 'published')
            ->when($this->filterMandatory !== '', fn ($q) => $q->where('is_mandatory', $this->filterMandatory === '1'))
            ->count();

        return [
            'totalEnrollments'     => $total,
            'completedEnrollments' => $completed,
            'avgScore'             => round($avgScore ?? 0, 1),
            'activeCourses'        => $activeCourses,
        ];
    }

    // ── Export ────────────────────────────────────────────────────────────────
    public function exportExcel()
    {
        $prepared = $this->prepareExportData();

        if (empty($prepared['rows'])) {
            session()->flash('warning', __('lms.no_report_export_data'));
            return;
        }

        return Excel::download(
            new ReportExport($prepared['rows'], $prepared['headings'], $prepared['title'], $prepared['filterInfo']),
            'rapor-' . $this->activeTab . '-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf()
    {
        $prepared = $this->prepareExportData();

        if (empty($prepared['rows'])) {
            session()->flash('warning', __('lms.no_report_export_data'));
            return;
        }

        $pdf = Pdf::loadView('pdf.report', [
            'title'       => $prepared['title'],
            'headings'    => $prepared['headings'],
            'rows'        => $prepared['rows'],
            'institution' => SystemSetting::get('institution_name', 'Devakent Hastanesi'),
            'filterInfo'  => $prepared['filterInfo'],
            'generatedAt' => now()->format('d.m.Y H:i'),
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'rapor-' . $this->activeTab . '-' . date('Y-m-d') . '.pdf'
        );
    }

    private function buildFilterInfo(): string
    {
        $parts = [];
        if ($this->dateFrom)            $parts[] = 'Başlangıç: ' . $this->dateFrom;
        if ($this->dateTo)              $parts[] = 'Bitiş: ' . $this->dateTo;
        if ($this->filterDepartment) {
            $dept = Department::find($this->filterDepartment);
            if ($dept) $parts[] = 'Departman: ' . $dept->name;
        }
        if ($this->filterStatus) {
            $map     = [
                'completed'   => __('lms.completed'),
                'in_progress' => __('lms.in_progress'),
                'not_started' => __('lms.not_started'),
            ];
            $parts[] = __('lms.status') . ': ' . ($map[$this->filterStatus] ?? $this->filterStatus);
        }
        if ($this->filterMandatory !== '') {
            $parts[] = $this->filterMandatory === '1' ? __('lms.mandatory_label') : __('lms.optional_label');
        }
        if ($this->search)              $parts[] = __('lms.search') . ': "' . $this->search . '"';

        return implode(' | ', $parts) ?: __('lms.no_filter_applied');
    }

    private function prepareExportData(): array
    {
        $data       = match ($this->activeTab) {
            'course'     => $this->getCourseReport(),
            'department' => $this->getDepartmentReport(),
            'staff'      => $this->getStaffReport(),
            'time'       => $this->getTimeReport(),
            default      => $this->getCourseReport(),
        };
        $reportData = $data['reportData'];
        $filterInfo = $this->buildFilterInfo();

        return match ($this->activeTab) {
            'course' => [
                'title'      => 'Eğitim Bazlı Rapor',
                'filterInfo' => $filterInfo,
                'headings'   => ['Eğitim', 'Kategori', 'Kayıt', 'Tamamlanan', 'Tamamlanma %', 'Ön Sınav Ort.', 'Son Sınav Ort.'],
                'rows'       => $reportData->map(fn ($r) => [
                    $r['title'], $r['category'], $r['enrollments'], $r['completed'],
                    '%' . $r['completion_rate'], $r['pre_exam_avg'], $r['post_exam_avg'],
                ])->toArray(),
            ],
            'department' => [
                'title'      => 'Departman Bazlı Rapor',
                'filterInfo' => $filterInfo,
                'headings'   => ['Departman', 'Personel', 'Kayıt', 'Tamamlanan', 'Tamamlanma %', 'Ön Sınav Ort.', 'Son Sınav Ort.'],
                'rows'       => $reportData->map(fn ($r) => [
                    $r['name'], $r['staff_count'], $r['enrollments'], $r['completed'],
                    '%' . $r['completion_rate'], $r['pre_exam_avg'], $r['post_exam_avg'],
                ])->toArray(),
            ],
            'staff' => [
                'title'      => 'Personel Bazlı Rapor',
                'filterInfo' => $filterInfo,
                'headings'   => ['Personel', 'Departman', 'Kayıt', 'Tamamlanan', 'Tamamlanma %', 'Ön Sınav Ort.', 'Son Sınav Ort.', 'Sertifika', 'Son Giriş'],
                'rows'       => $reportData->map(fn ($r) => [
                    $r['name'], $r['department'], $r['enrollments'], $r['completed'],
                    '%' . $r['completion_rate'], $r['pre_exam_avg'], $r['post_exam_avg'], $r['certificates'], $r['last_login'],
                ])->toArray(),
            ],
            'time' => [
                'title'      => 'Zaman Bazlı Rapor',
                'filterInfo' => $filterInfo,
                'headings'   => ['Dönem', 'Toplam Kayıt', 'Tamamlanan', 'Tamamlanma %'],
                'rows'       => $reportData->map(fn ($r) => [
                    $r['month'], $r['total'], $r['completed'], '%' . $r['completion_rate'],
                ])->toArray(),
            ],
        };
    }

    // ── Sort helper ───────────────────────────────────────────────────────────
    private function applySort($collection, array $allowed): \Illuminate\Support\Collection
    {
        if (!$this->sortField || !in_array($this->sortField, $allowed)) {
            return $collection;
        }
        return $this->sortDirection === 'asc'
            ? $collection->sortBy($this->sortField)->values()
            : $collection->sortByDesc($this->sortField)->values();
    }

    // ── Report data ───────────────────────────────────────────────────────────
    private function getCourseReport(): array
    {
        $courses = Course::where('status', 'published')
            ->with('category')
            ->withCount('questions')
            ->when($this->filterMandatory !== '', fn ($q) => $q->where('is_mandatory', $this->filterMandatory === '1'))
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->get();

        if ($courses->isEmpty()) {
            return ['reportData' => collect()];
        }

        $courseIds = $courses->pluck('id');

        // Enrollment counts per course — tek sorgu
        $enrollBase = Enrollment::whereIn('course_id', $courseIds)
            ->when($this->dateFrom,         fn ($q) => $q->where('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,           fn ($q) => $q->where('created_at', '<=', $this->dateTo . ' 23:59:59'))
            ->when($this->filterDepartment, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('department_id', $this->filterDepartment)))
            ->when($this->filterStatus,     fn ($q) => $q->where('status', $this->filterStatus));

        $enrollCounts = (clone $enrollBase)
            ->select('course_id', DB::raw('COUNT(*) as total'), DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"))
            ->groupBy('course_id')
            ->get()
            ->keyBy('course_id');

        $enrollmentIds = (clone $enrollBase)->pluck('id');

        // Exam ortalamaları per course — tek sorgu
        $examAvgs = collect();
        if ($enrollmentIds->isNotEmpty()) {
            $examAvgs = ExamAttempt::select(
                    'enrollments.course_id',
                    'exam_type',
                    DB::raw('ROUND(AVG(exam_attempts.score), 1) as avg_score')
                )
                ->join('enrollments', 'enrollments.id', '=', 'exam_attempts.enrollment_id')
                ->whereIn('exam_attempts.enrollment_id', $enrollmentIds)
                ->whereNotNull('exam_attempts.finished_at')
                ->whereIn('exam_type', ['pre_exam', 'post_exam'])
                ->groupBy('enrollments.course_id', 'exam_type')
                ->get()
                ->groupBy('course_id')
                ->map(fn ($items) => $items->keyBy('exam_type'));
        }

        $reportData = $courses->map(function ($course) use ($enrollCounts, $examAvgs) {
            $enroll    = $enrollCounts->get($course->id);
            $total     = $enroll?->total ?? 0;
            $completed = $enroll?->completed ?? 0;
            $courseExams = $examAvgs->get($course->id, collect());

            return [
                'title'           => $course->title,
                'category'        => $course->category?->name ?? '—',
                'category_color'  => $course->category?->color ?? '#6b7280',
                'enrollments'     => $total,
                'completed'       => $completed,
                'completion_rate' => $total > 0 ? round($completed / $total * 100, 1) : 0,
                'pre_exam_avg'    => $courseExams->get('pre_exam')?->avg_score ?? 0,
                'post_exam_avg'   => $courseExams->get('post_exam')?->avg_score ?? 0,
                'questions'       => $course->questions_count,
                'is_mandatory'    => $course->is_mandatory,
            ];
        });

        return ['reportData' => $this->applySort($reportData, ['title', 'enrollments', 'completed', 'completion_rate', 'pre_exam_avg', 'post_exam_avg'])];
    }

    private function getDepartmentReport(): array
    {
        $departments = Department::where('is_active', true)
            ->when($this->filterDepartment, fn ($q) => $q->where('id', $this->filterDepartment))
            ->when($this->search,           fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->withCount('users')
            ->get();

        if ($departments->isEmpty()) {
            return ['reportData' => collect()];
        }

        $deptIds = $departments->pluck('id');

        // Tüm dept user'larını tek sorguda al
        $deptUsers = User::role('staff')
            ->select('id', 'department_id')
            ->whereIn('department_id', $deptIds)
            ->get()
            ->groupBy('department_id');

        $allUserIds = $deptUsers->flatten()->pluck('id');

        if ($allUserIds->isEmpty()) {
            $reportData = $departments->map(fn ($dept) => [
                'name'            => $dept->name,
                'staff_count'     => $dept->users_count,
                'enrollments'     => 0,
                'completed'       => 0,
                'completion_rate' => 0,
                'pre_exam_avg'    => 0,
                'post_exam_avg'   => 0,
            ]);
            return ['reportData' => $this->applySort($reportData, ['name', 'staff_count', 'enrollments', 'completed', 'completion_rate', 'pre_exam_avg', 'post_exam_avg'])];
        }

        // Enrollment counts per user — tek sorgu
        $enrollBase = Enrollment::whereIn('user_id', $allUserIds)
            ->when($this->dateFrom,        fn ($q) => $q->where('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,          fn ($q) => $q->where('created_at', '<=', $this->dateTo . ' 23:59:59'))
            ->when($this->filterStatus,    fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterMandatory !== '', fn ($q) => $q->whereHas('course', fn ($c) => $c->where('is_mandatory', $this->filterMandatory === '1')));

        $enrollCounts = (clone $enrollBase)
            ->select('user_id', DB::raw('COUNT(*) as total'), DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"))
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $enrollmentIds = (clone $enrollBase)->pluck('id');

        // Exam ortalamaları per user — tek sorgu
        $examAvgs = collect();
        if ($enrollmentIds->isNotEmpty()) {
            $examAvgs = ExamAttempt::select(
                    'enrollments.user_id',
                    'exam_type',
                    DB::raw('ROUND(AVG(exam_attempts.score), 1) as avg_score')
                )
                ->join('enrollments', 'enrollments.id', '=', 'exam_attempts.enrollment_id')
                ->whereIn('exam_attempts.enrollment_id', $enrollmentIds)
                ->whereNotNull('exam_attempts.finished_at')
                ->whereIn('exam_type', ['pre_exam', 'post_exam'])
                ->groupBy('enrollments.user_id', 'exam_type')
                ->get()
                ->groupBy('user_id')
                ->map(fn ($items) => $items->keyBy('exam_type'));
        }

        $reportData = $departments->map(function ($dept) use ($deptUsers, $enrollCounts, $examAvgs) {
            $userIds    = $deptUsers->get($dept->id, collect())->pluck('id');
            $total      = 0;
            $completed  = 0;
            $preScores  = [];
            $postScores = [];

            foreach ($userIds as $uid) {
                $enroll     = $enrollCounts->get($uid);
                $total     += $enroll?->total ?? 0;
                $completed += $enroll?->completed ?? 0;
                $userExams  = $examAvgs->get($uid, collect());
                if ($pre = $userExams->get('pre_exam'))   $preScores[]  = $pre->avg_score;
                if ($post = $userExams->get('post_exam')) $postScores[] = $post->avg_score;
            }

            return [
                'name'            => $dept->name,
                'staff_count'     => $dept->users_count,
                'enrollments'     => $total,
                'completed'       => $completed,
                'completion_rate' => $total > 0 ? round($completed / $total * 100, 1) : 0,
                'pre_exam_avg'    => count($preScores)  > 0 ? round(array_sum($preScores)  / count($preScores),  1) : 0,
                'post_exam_avg'   => count($postScores) > 0 ? round(array_sum($postScores) / count($postScores), 1) : 0,
            ];
        });

        return ['reportData' => $this->applySort($reportData, ['name', 'staff_count', 'enrollments', 'completed', 'completion_rate', 'pre_exam_avg', 'post_exam_avg'])];
    }

    private function getStaffReport(): array
    {
        $staff = User::role('staff')
            ->with('department')
            ->when($this->filterDepartment, fn ($q) => $q->where('department_id', $this->filterDepartment))
            ->when($this->search, fn ($q) => $q->where(fn ($q2) =>
                $q2->where('first_name', 'like', "%{$this->search}%")
                   ->orWhere('last_name', 'like', "%{$this->search}%")
                   ->orWhere('email', 'like', "%{$this->search}%")
            ))
            ->withCount(['certificates'])
            ->orderBy('first_name')
            ->get();

        if ($staff->isEmpty()) {
            return ['reportData' => collect()];
        }

        $userIds = $staff->pluck('id');

        // Enrollment counts per user — tek sorgu
        $enrollBase = Enrollment::whereIn('user_id', $userIds)
            ->when($this->dateFrom,        fn ($q) => $q->where('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,          fn ($q) => $q->where('created_at', '<=', $this->dateTo . ' 23:59:59'))
            ->when($this->filterStatus,    fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterMandatory !== '', fn ($q) => $q->whereHas('course', fn ($c) => $c->where('is_mandatory', $this->filterMandatory === '1')));

        $enrollCounts = (clone $enrollBase)
            ->select('user_id', DB::raw('COUNT(*) as total'), DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"))
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $enrollmentIds = (clone $enrollBase)->pluck('id');

        // Exam ortalamaları per user — tek sorgu
        $examAvgs = collect();
        if ($enrollmentIds->isNotEmpty()) {
            $examAvgs = ExamAttempt::select(
                    'enrollments.user_id',
                    'exam_type',
                    DB::raw('ROUND(AVG(exam_attempts.score), 1) as avg_score')
                )
                ->join('enrollments', 'enrollments.id', '=', 'exam_attempts.enrollment_id')
                ->whereIn('exam_attempts.enrollment_id', $enrollmentIds)
                ->whereNotNull('exam_attempts.finished_at')
                ->whereIn('exam_type', ['pre_exam', 'post_exam'])
                ->groupBy('enrollments.user_id', 'exam_type')
                ->get()
                ->groupBy('user_id')
                ->map(fn ($items) => $items->keyBy('exam_type'));
        }

        $reportData = $staff->map(function ($user) use ($enrollCounts, $examAvgs) {
            $enroll    = $enrollCounts->get($user->id);
            $total     = $enroll?->total ?? 0;
            $completed = $enroll?->completed ?? 0;
            $userExams = $examAvgs->get($user->id, collect());

            return [
                'id'              => $user->id,
                'name'            => $user->full_name,
                'department'      => $user->department?->name ?? '—',
                'enrollments'     => $total,
                'completed'       => $completed,
                'completion_rate' => $total > 0 ? round($completed / $total * 100, 1) : 0,
                'pre_exam_avg'    => $userExams->get('pre_exam')?->avg_score ?? 0,
                'post_exam_avg'   => $userExams->get('post_exam')?->avg_score ?? 0,
                'certificates'    => $user->certificates_count,
                'last_login'      => $user->last_login_at?->format('d.m.Y H:i') ?? 'Henüz giriş yok',
            ];
        });

        return ['reportData' => $this->applySort($reportData, ['name', 'department', 'enrollments', 'completed', 'completion_rate', 'pre_exam_avg', 'post_exam_avg', 'certificates'])];
    }

    private function getTimeReport(): array
    {
        $format = $this->timeGrouping === 'weekly' ? '%Y-%u' : '%Y-%m';
        $from   = $this->dateFrom ?: now()->subMonths(6)->format('Y-m-d');
        $to     = $this->dateTo   ?: now()->format('Y-m-d');

        $rows = Enrollment::select(
                DB::raw("DATE_FORMAT(created_at, '{$format}') as month"),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"),
            )
            ->where('created_at', '>=', $from)
            ->where('created_at', '<=', $to . ' 23:59:59')
            ->when($this->filterDepartment, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('department_id', $this->filterDepartment)))
            ->when($this->filterStatus,     fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterMandatory !== '', fn ($q) => $q->whereHas('course', fn ($c) => $c->where('is_mandatory', $this->filterMandatory === '1')))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn ($row) => [
                'month'           => $row->month,
                'total'           => $row->total,
                'completed'       => $row->completed,
                'completion_rate' => $row->total > 0 ? round($row->completed / $row->total * 100, 1) : 0,
            ]);

        return ['reportData' => collect($rows)];
    }
}
