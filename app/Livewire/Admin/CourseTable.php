<?php

namespace App\Livewire\Admin;

use App\Exports\CourseExport;
use App\Models\Category;
use App\Models\Course;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;
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

    // Bulk selection
    public array $selectedCourses = [];
    public bool $selectAll = false;

    // Modals
    public bool $showDeleteModal = false;
    public bool $showDetailModal = false;
    public bool $showBulkDeleteModal = false;
    public ?int $deletingId = null;
    public ?int $viewingId = null;

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

    // Tab butonları için özel method — wire:target ile hedeflenebilir
    public function filterByStatus(string $status): void
    {
        $this->filterStatus = $status;
        $this->resetPage();
    }

    // ── Sorting ──
    private array $allowedSortFields = ['title', 'created_at', 'questions_count', 'enrollments_count', 'status', 'category_id'];

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
        session()->flash('success', __('lms.course_bulk_published', ['count' => count($this->selectedCourses)]));
        $this->selectedCourses = [];
        $this->selectAll = false;
    }

    public function bulkDraft(): void
    {
        Course::whereIn('id', $this->selectedCourses)->update(['status' => 'draft']);
        Cache::forget('admin.course_status_counts');
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
        if ($this->getFilteredQuery()->doesntExist()) {
            session()->flash('warning', __('lms.no_export_data'));
            return;
        }

        return Excel::download(
            new CourseExport($this->search, $this->filterCategory, $this->filterStatus, $this->filterMandatory),
            'egitimler_' . date('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf()
    {
        $courses = $this->getFilteredQuery()
            ->with(['category', 'departments'])
            ->withCount(['questions', 'enrollments', 'enrollments as completed_enrollments_count' => fn ($q) => $q->where('status', 'completed')])
            ->get();

        $pdf = Pdf::loadView('exports.courses-pdf', ['courses' => $courses])
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'egitimler_' . date('Y-m-d') . '.pdf'
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

        // Kategoriler nadiren değişir — 10 dk cache
        $categories = Cache::remember('admin.course_categories', 600, fn () =>
            Category::orderBy('name')->get()
        );

        // 3 ayrı COUNT yerine tek GROUP BY + 1 dk cache
        $statusCounts = Cache::remember('admin.course_status_counts', 60, fn () =>
            Course::selectRaw('status, COUNT(*) as cnt')->groupBy('status')->pluck('cnt', 'status')
        );
        $totalCourses   = $statusCounts->sum();
        $publishedCount = $statusCounts->get('published', 0);
        $draftCount     = $statusCounts->get('draft', 0);

        // Detail modal — sadece modal açıkken yükle
        $viewingCourse = $this->showDetailModal && $this->viewingId
            ? Course::with(['category', 'creator', 'departments', 'enrollments.user', 'videos'])
                ->withCount(['questions', 'enrollments', 'enrollments as completed_enrollments_count' => fn ($q) => $q->where('status', 'completed')])
                ->find($this->viewingId)
            : null;

        return view('livewire.admin.course-table', compact(
            'courses', 'categories', 'totalCourses', 'publishedCount', 'draftCount', 'viewingCourse'
        ));
    }
}
