<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterDepartment = '';
    public string $filterStatus = '';

    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showDetailModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;
    public ?int $viewingId = null;

    // Form fields
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';
    public string $phone = '';
    public string $registration_number = '';
    public string $title = '';
    public ?int $department_id = null;
    public string $hire_date = '';
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingId)],
            'password' => $this->editingId ? 'nullable|string|min:6' : 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'registration_number' => ['nullable', 'string', 'max:50', Rule::unique('users', 'registration_number')->ignore($this->editingId)],
            'title' => 'nullable|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'hire_date' => 'nullable|date',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'first_name.required' => 'Ad alanı zorunludur.',
        'last_name.required' => 'Soyad alanı zorunludur.',
        'email.required' => 'E-posta alanı zorunludur.',
        'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
        'password.required' => 'Şifre alanı zorunludur.',
        'password.min' => 'Şifre en az 6 karakter olmalıdır.',
        'registration_number.unique' => 'Bu sicil numarası zaten kullanılıyor.',
        'department_id.required' => 'Departman seçimi zorunludur.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterDepartment(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editingId = $user->id;
        $this->first_name = $user->first_name ?? '';
        $this->last_name = $user->last_name ?? '';
        $this->email = $user->email;
        $this->password = '';
        $this->phone = $user->phone ?? '';
        $this->registration_number = $user->registration_number ?? '';
        $this->title = $user->title ?? '';
        $this->department_id = $user->department_id;
        $this->hire_date = $user->hire_date ? $user->hire_date->format('Y-m-d') : '';
        $this->is_active = $user->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => "{$this->first_name} {$this->last_name}",
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'registration_number' => $this->registration_number ?: null,
            'title' => $this->title ?: null,
            'department_id' => $this->department_id,
            'hire_date' => $this->hire_date ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->update($data);
            session()->flash('success', 'Personel bilgileri güncellendi.');
        } else {
            $user = User::create($data);
            $user->assignRole('staff');
            session()->flash('success', 'Yeni personel oluşturuldu.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function viewDetail(int $id): void
    {
        $this->viewingId = $id;
        $this->showDetailModal = true;
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $user = User::findOrFail($this->deletingId);

        if ($user->enrollments()->count() > 0) {
            session()->flash('error', 'Bu personelin kayıtlı eğitimleri bulunmaktadır. Önce eğitim kayıtlarını kaldırın.');
            $this->showDeleteModal = false;
            return;
        }

        $user->delete(); // SoftDelete
        session()->flash('success', 'Personel silindi.');
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function toggleActive(int $id): void
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        session()->flash('success', $user->is_active ? 'Personel aktif edildi.' : 'Personel pasif edildi.');
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'first_name', 'last_name', 'email', 'password', 'phone', 'registration_number', 'title', 'department_id', 'hire_date']);
        $this->is_active = true;
    }

    public function render()
    {
        $staff = User::query()
            ->role('staff')
            ->with('department')
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('first_name', 'like', "%{$this->search}%")
                       ->orWhere('last_name', 'like', "%{$this->search}%")
                       ->orWhere('email', 'like', "%{$this->search}%")
                       ->orWhere('registration_number', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterDepartment, fn ($q) => $q->where('department_id', $this->filterDepartment))
            ->when($this->filterStatus !== '', function ($q) {
                $q->where('is_active', $this->filterStatus === '1');
            })
            ->withCount('enrollments')
            ->orderBy('first_name')
            ->paginate(15);

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        // Detail modal user
        $viewingUser = $this->viewingId ? User::with(['department', 'enrollments.course'])->withCount(['enrollments', 'certificates'])->find($this->viewingId) : null;

        return view('livewire.admin.staff-table', compact('staff', 'departments', 'viewingUser'));
    }
}
