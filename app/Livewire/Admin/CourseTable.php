<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class CourseTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterCategory = '';
    public string $filterStatus = '';

    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

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

    public function render()
    {
        $courses = Course::query()
            ->with(['category', 'creator', 'departments'])
            ->withCount(['questions', 'enrollments'])
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterCategory, fn ($q) => $q->where('category_id', $this->filterCategory))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('created_at')
            ->paginate(10);

        $categories = Category::orderBy('name')->get();

        return view('livewire.admin.course-table', compact('courses', 'categories'));
    }
}
