<?php

namespace App\Livewire\Admin;

use App\Exports\CourseExport;
use App\Models\Category;
use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class CourseTable extends AdminComponent
{
    use WithPagination;

    public string $search = '';
    public string $filterCategory = '';
    public string $filterStatus = '';
    public string $filterMandatory = '';

    // Sorting
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // View mode
    public string $viewMode = 'table';

    // Bulk selection
    public array $selectedCourses = [];
    public bool $selectAll = false;

    // Modals
    public bool $showDeleteModal = false;
    public bool $showDetailModal = false;
    public bool $showBulkDeleteModal = false;
    public bool $showBulkAssignModal = false;
    public ?int $deletingId = null;
    public ?int $viewingId = null;
    public ?int $bulkAssignDepartmentId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterCategory(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingFilterMandatory(): void
    {
        $this->resetPage();
    }

    public function filterByStatus(string $status): void
    {
        $this->filterStatus = $status;
        $this->resetPage();
    }

    // ── Sorting ──
    private array $allowedSortFields = [
        'title', 'created_at', 'updated_at', 'questions_count',
        'enrollments_count', 'status', 'category_id', 'end_date',
        'exam_duration_minutes',
    ];

    public function sortBy(string $field): void
    {
        if (!in_array($field, $this->allowedSortFields)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function placeholder(): string
    {
        return view('livewire.placeholders.skeleton')->render();
    }

    // ── View Mode ──
    public function setViewMode(string $mode): void
    {
        $this->viewMode = in_array($mode, ['table', 'card']) ? $mode : 'table';
    }

    // ── Bulk Selection ──
    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->selectedCourses = $this->getFilteredQuery()->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        } else {
            $this->selectedCourses = [];
        }
    }

    public function updatedSelectedCourses(): void
    {
        $this->selectAll = false;
    }

    public function bulkPublish(): void
    {
        Course::whereIn('id', $this->selectedCourses)->update(['status' => 'published']);
        Cache::forget('admin.course_status_counts');
        Cache::forget('admin.course_stats');
        session()->flash('success', __('lms.course_bulk_published', ['count' => count($this->selectedCourses)]));
        $this->selectedCourses = [];
        $this->selectAll = false;
    }

    public function bulkDraft(): void
    {
        Course::whereIn('id', $this->selectedCourses)->update(['status' => 'draft']);
        Cache::forget('admin.course_status_counts');
        Cache::forget('admin.course_stats');
        session()->flash('success', __('lms.course_bulk_drafted', ['count' => count($this->selectedCourses)]));
        $this->selectedCourses = [];
        $this->selectAll = false;
    }

    public function confirmBulkDelete(): void
    {
        $this->showBulkDeleteModal = true;
    }

    public function bulkDelete(): void
    {
        $courses = Course::whereIn('id', $this->selectedCourses)->withCount('enrollments')->get();
        $deleted = 0;
        $skipped = 0;

        foreach ($courses as $course) {
            if ($course->enrollments_count > 0) {
                $skipped++;
                continue;
            }
            $course->questions()->delete();
            $course->departments()->detach();
            $course->delete();
            $deleted++;
        }

        if ($skipped > 0) {
            $message = __('lms.course_bulk_deleted', ['count' => $deleted, 'skipped' => $skipped]);
        } else {
            $message = __('lms.course_bulk_deleted_clean', ['count' => $deleted]);
        }
        session()->flash('success', $message);
        $this->selectedCourses = [];
        $this->selectAll = false;
        $this->showBulkDeleteModal = false;
        Cache::forget('admin.course_status_counts');
        Cache::forget('admin.course_stats');
    }

    // ── Bulk Assign Department ──
    public function openBulkAssign(): void
    {
        $this->showBulkAssignModal = true;
        $this->bulkAssignDepartmentId = null;
    }

    public function bulkAssignDepartment(): void
    {
        if (!$this->bulkAssignDepartmentId) {
            return;
        }

        $courses = Course::whereIn('id', $this->selectedCourses)->get();
        foreach ($courses as $course) {
            $course->departments()->syncWithoutDetaching([$this->bulkAssignDepartmentId]);
        }

        session()->flash('success', count($this->selectedCourses) . ' eğitim departmana atandı.');
        $this->selectedCourses = [];
        $this->selectAll = false;
        $this->showBulkAssignModal = false;
        $this->bulkAssignDepartmentId = null;
    }

    // ── Detail Modal ──
    public function viewDetail(int $id): void
    {
        $this->viewingId = $id;
        $this->showDetailModal = true;
    }

    // ── Export ──
    public function exportExcel()
    {
        $query = !empty($this->selectedCourses)
            ? Course::whereIn('id', $this->selectedCourses)
            : $this->getFilteredQuery();

        if ($query->doesntExist()) {
            session()->flash('warning', __('lms.no_export_data'));
            return;
        }

        $selectedIds = !empty($this->selectedCourses) ? $this->selectedCourses : [];

        return response()->streamDownload(function () use ($selectedIds) {
            echo Excel::raw(
                new CourseExport($this->search, $this->filterCategory, $this->filterStatus, $this->filterMandatory, $selectedIds),
                \Maatwebsite\Excel\Excel::XLSX
            );
        }, 'egitimler_' . date('Y-m-d') . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function exportPdf()
    {
        $query = !empty($this->selectedCourses)
            ? Course::whereIn('id', $this->selectedCourses)
            : $this->getFilteredQuery();

        $courses = $query
            ->with(['category', 'departments'])
            ->withCount(['questions', 'enrollments', 'enrollments as completed_enrollments_count' => fn ($q) => $q->where('status', 'completed')])
            ->get();

        if ($courses->isEmpty()) {
            session()->flash('warning', __('lms.no_export_data'));
            return;
        }

        $pdf = Pdf::loadView('exports.courses-pdf', ['courses' => $courses])
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'egitimler_' . date('Y-m-d') . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    // ── CRUD ──
    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $course = Course::findOrFail($this->deletingId);

        if ($course->enrollments()->count() > 0) {
            session()->flash('error', __('lms.course_has_enrollments'));
            $this->showDeleteModal = false;
            return;
        }

        $course->questions()->delete();
        $course->departments()->detach();
        $course->delete();
        Cache::forget('admin.course_status_counts');
        Cache::forget('admin.course_stats');

        session()->flash('success', __('lms.course_deleted_msg'));
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function toggleStatus(int $id): void
    {
        $course = Course::findOrFail($id);
        $newStatus = $course->status === 'published' ? 'draft' : 'published';
        $course->update(['status' => $newStatus]);
        Cache::forget('admin.course_status_counts');
        Cache::forget('admin.course_stats');
        session()->flash('success', $newStatus === 'published' ? __('lms.course_published') : __('lms.course_drafted'));
    }

    // ── Query Builder ──
    private function getFilteredQuery()
    {
        return Course::query()
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterCategory, fn ($q) => $q->where('category_id', $this->filterCategory))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterMandatory !== '', function ($q) {
                $q->where('is_mandatory', $this->filterMandatory === '1');
            });
    }

    public function render()
    {
        $courses = $this->getFilteredQuery()
            ->with(['category', 'creator', 'departments'])
            ->withCount(['questions', 'enrollments', 'enrollments as completed_enrollments_count' => function ($q) {
                $q->where('status', 'completed');
            }])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $categories = Cache::remember('admin.course_categories', 600, fn () =>
            Category::orderBy('name')->get()
        );

        $statusCounts = Cache::remember('admin.course_status_counts', 60, fn () =>
            Course::selectRaw('status, COUNT(*) as cnt')->groupBy('status')->pluck('cnt', 'status')
        );
        $totalCourses   = $statusCounts->sum();
        $publishedCount = $statusCounts->get('published', 0);
        $draftCount     = $statusCounts->get('draft', 0);

        // Summary stats
        $stats = Cache::remember('admin.course_stats', 60, function () {
            $totalEnrollments = Enrollment::count();
            $completedEnrollments = Enrollment::where('status', 'completed')->count();
            $avgCompletion = $totalEnrollments > 0 ? round($completedEnrollments / $totalEnrollments * 100) : 0;
            $expiredCount = Course::where('end_date', '<', now())
                ->where('status', 'published')
                ->count();

            return compact('totalEnrollments', 'avgCompletion', 'expiredCount');
        });

        // Departments for bulk assign
        $departments = Cache::remember('departments.all', now()->addHours(6),
            fn () => Department::where('is_active', true)->orderBy('name')->get()
        );

        // Detail modal
        $viewingCourse = $this->showDetailModal && $this->viewingId
            ? Course::with(['category', 'creator', 'departments', 'enrollments.user', 'videos'])
                ->withCount(['questions', 'enrollments', 'enrollments as completed_enrollments_count' => fn ($q) => $q->where('status', 'completed')])
                ->find($this->viewingId)
            : null;

        return view('livewire.admin.course-table', compact(
            'courses', 'categories', 'totalCourses', 'publishedCount', 'draftCount',
            'viewingCourse', 'stats', 'departments'
        ));
    }
}
