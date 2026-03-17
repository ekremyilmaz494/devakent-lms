<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentTable extends AdminComponent
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    public string $name = '';
    public string $description = '';
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:150|unique:departments,name,' . $this->editingId,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => __('lms.val_name_required'),
            'name.unique'   => __('lms.val_name_unique'),
            'name.max'      => __('lms.val_dept_name_max'),
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['editingId', 'name', 'description', 'is_active']);
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $dept = Department::findOrFail($id);
        $this->editingId = $dept->id;
        $this->name = $dept->name;
        $this->description = $dept->description ?? '';
        $this->is_active = $dept->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            Department::findOrFail($this->editingId)->update($data);
            session()->flash('success', __('lms.department_updated'));
        } else {
            Department::create($data);
            session()->flash('success', __('lms.department_created'));
        }

        $this->showModal = false;
        $this->reset(['editingId', 'name', 'description', 'is_active']);
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $dept = Department::findOrFail($this->deletingId);

        if ($dept->users()->count() > 0) {
            session()->flash('error', __('lms.cannot_delete_has_staff'));
            $this->showDeleteModal = false;
            return;
        }

        $dept->delete();
        session()->flash('success', __('lms.department_deleted'));
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $departments = Department::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->withCount('users')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.department-table', compact('departments'));
    }
}
