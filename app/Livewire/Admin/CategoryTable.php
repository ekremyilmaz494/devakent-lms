<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryTable extends AdminComponent
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    public string $name = '';
    public string $color = '#3B82F6';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:categories,name,' . $this->editingId,
            'color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => __('lms.val_category_name_req'),
            'name.unique'   => __('lms.val_category_name_unique'),
            'color.regex'   => __('lms.val_color_regex'),
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['editingId', 'name', 'color']);
        $this->color = '#3B82F6';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $cat = Category::findOrFail($id);
        $this->editingId = $cat->id;
        $this->name = $cat->name;
        $this->color = $cat->color;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = ['name' => $this->name, 'color' => $this->color];

        if ($this->editingId) {
            Category::findOrFail($this->editingId)->update($data);
            session()->flash('success', __('lms.category_updated'));
        } else {
            Category::create($data);
            session()->flash('success', __('lms.category_created'));
        }

        $this->showModal = false;
        $this->reset(['editingId', 'name', 'color']);
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $cat = Category::findOrFail($this->deletingId);

        if ($cat->courses()->count() > 0) {
            session()->flash('error', __('lms.cannot_delete_has_courses'));
            $this->showDeleteModal = false;
            return;
        }

        $cat->delete();
        session()->flash('success', __('lms.category_deleted'));
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->withCount('courses')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.category-table', compact('categories'));
    }
}
