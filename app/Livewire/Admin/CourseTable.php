<?php

namespace App\Livewire\Admin;

use App\Exports\CourseExport;
use App\Models\Category;
use App\Models\Course;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class CourseTable extends Component
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
        session()->flash('success', count($this->selectedCourses) . ' eğitim yayınlandı.');
        $this->selectedCourses = [];
        $this->selectAll = false;
    }

    public function bulkDraft(): void
    {
        Course::whereIn('id', $this->selectedCourses)->update(['status' => 'draft']);
        session()->flash('success', count($this->selectedCourses) . ' eğitim taslağa alındı.');
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

        $message = "{$deleted} eğitim silindi.";
        if ($skipped > 0) {
            $message .= " {$skipped} eğitim kayıtlı personel nedeniyle silinemedi.";
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
            session()->flash('error', 'Bu eğitime kayıtlı personel bulunmaktadır. Önce kayıtları kaldırın.');
            $this->showDeleteModal = false;
            return;
        }

        $course->questions()->delete();
        $course->departments()->detach();
        $course->delete();

        session()->flash('success', 'Eğitim silindi.');
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function toggleStatus(int $id): void
    {
        $course = Course::findOrFail($id);
        $newStatus = $course->status === 'published' ? 'draft' : 'published';
        $course->update(['status' => $newStatus]);
        session()->flash('success', $newStatus === 'published' ? 'Eğitim yayınlandı.' : 'Eğitim taslağa alındı.');
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

        $categories = Category::orderBy('name')->get();
        $totalCourses = Course::count();
        $publishedCount = Course::where('status', 'published')->count();
        $draftCount = Course::where('status', 'draft')->count();

        // Detail modal course
        $viewingCourse = $this->viewingId
            ? Course::with(['category', 'creator', 'departments', 'enrollments.user', 'videos'])
                ->withCount(['questions', 'enrollments', 'enrollments as completed_enrollments_count' => fn ($q) => $q->where('status', 'completed')])
                ->find($this->viewingId)
            : null;

        return view('livewire.admin.course-table', compact(
            'courses', 'categories', 'totalCourses', 'publishedCount', 'draftCount', 'viewingCourse'
        ));
    }
}
