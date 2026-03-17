<?php

namespace App\Livewire\Admin;

use App\Exports\StaffExport;
use App\Imports\StaffImport;
use App\Models\Department;
use App\Models\SystemSetting;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class StaffTable extends AdminComponent
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterDepartment = '';
    public string $filterStatus = '';

    // Sorting
    public string $sortField = 'first_name';
    public string $sortDirection = 'asc';

    // Bulk selection
    public array $selectedStaff = [];
    public bool $selectAll = false;

    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showDetailModal = false;
    public bool $showImportModal = false;
    public bool $showBulkDeleteModal = false;
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

    // Import
    public $importFile = null;

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
            'department_id' => 'required|exists:departments,id,is_active,1',
            'hire_date' => 'nullable|date',
            'is_active' => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'first_name.required'          => __('lms.val_name_required'),
            'last_name.required'           => __('lms.val_name_required'),
            'email.required'               => __('lms.val_email_required'),
            'email.unique'                 => __('lms.val_email_unique'),
            'password.required'            => __('lms.val_password_required'),
            'password.min'                 => __('lms.val_password_min', ['min' => 6]),
            'registration_number.unique'   => __('lms.val_registration_unique'),
            'department_id.required'       => __('lms.val_department_required'),
        ];
    }

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

    public function openImport(): void
    {
        $this->importFile = null;
        $this->showImportModal = true;
    }

    public function importStaff(): void
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'importFile.required' => __('lms.val_import_required'),
            'importFile.mimes'    => __('lms.val_import_mimes'),
            'importFile.max'      => __('lms.val_import_max'),
        ]);

        $import = new StaffImport();
        Excel::import($import, $this->importFile->getRealPath());

        $message = __('lms.staff_imported', ['count' => $import->imported]);
        if ($import->skipped > 0) {
            $message .= ' ' . __('lms.staff_imported_skipped', ['skipped' => $import->skipped]);
        }

        if (count($import->errors) > 0) {
            session()->flash('importErrors', array_slice($import->errors, 0, 5));
        }

        session()->flash('success', $message);
        $this->showImportModal = false;
        $this->importFile = null;
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
            $data['password'] = $this->password; // 'hashed' cast otomatik hash'ler
        }

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->update($data);
            session()->flash('success', __('lms.staff_updated'));
        } else {
            $data['email_verified_at'] = now();
            $user = User::create($data);
            $user->assignRole('staff');
            session()->flash('success', __('lms.staff_created'));
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
            session()->flash('error', __('lms.cannot_delete_has_enrollments'));
            $this->showDeleteModal = false;
            return;
        }

        $user->delete(); // SoftDelete
        session()->flash('success', __('lms.staff_deleted'));
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function toggleActive(int $id): void
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        session()->flash('success', $user->is_active ? __('lms.staff_activated') : __('lms.staff_deactivated'));
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'first_name', 'last_name', 'email', 'password', 'phone', 'registration_number', 'title', 'department_id', 'hire_date']);
        $this->is_active = true;
    }

    // ── Sorting ──
    private array $allowedSortFields = ['first_name', 'last_name', 'email', 'registration_number', 'department_id', 'last_login_at', 'is_active', 'created_at'];

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
            $this->selectedStaff = $this->getFilteredQuery()
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedStaff = [];
        }
    }

    public function updatedSelectedStaff(): void
    {
        $this->selectAll = false;
    }

    public function bulkActivate(): void
    {
        User::whereIn('id', $this->selectedStaff)->update(['is_active' => true]);
        session()->flash('success', __('lms.staff_bulk_activated', ['count' => count($this->selectedStaff)]));
        $this->selectedStaff = [];
        $this->selectAll = false;
    }

    public function bulkDeactivate(): void
    {
        User::whereIn('id', $this->selectedStaff)->update(['is_active' => false]);
        session()->flash('success', __('lms.staff_bulk_deactivated', ['count' => count($this->selectedStaff)]));
        $this->selectedStaff = [];
        $this->selectAll = false;
    }

    public function confirmBulkDelete(): void
    {
        $this->showBulkDeleteModal = true;
    }

    public function bulkDelete(): void
    {
        $users = User::whereIn('id', $this->selectedStaff)->withCount('enrollments')->get();
        $deleted = 0;
        $skipped = 0;

        foreach ($users as $user) {
            if ($user->enrollments_count > 0) {
                $skipped++;
                continue;
            }
            $user->delete();
            $deleted++;
        }

        if ($skipped > 0) {
            $message = __('lms.staff_bulk_deleted', ['count' => $deleted, 'skipped' => $skipped]);
        } else {
            $message = __('lms.staff_bulk_deleted_clean', ['count' => $deleted]);
        }
        session()->flash('success', $message);
        $this->selectedStaff = [];
        $this->selectAll = false;
        $this->showBulkDeleteModal = false;
    }

    // ── Export ──
    public function exportExcel()
    {
        if ($this->getFilteredQuery()->doesntExist()) {
            session()->flash('warning', __('lms.no_export_data'));
            return;
        }

        return Excel::download(
            new StaffExport($this->search, $this->filterDepartment, $this->filterStatus),
            'personeller_' . date('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf()
    {
        if ($this->getFilteredQuery()->doesntExist()) {
            session()->flash('warning', __('lms.no_export_data'));
            return;
        }

        $staff = $this->getFilteredQuery()
            ->with('department')
            ->withCount([
                'enrollments',
                'enrollments as completed_enrollments_count' => fn ($q) => $q->where('status', 'completed'),
            ])
            ->get();

        $pdf = Pdf::loadView('exports.staff-pdf', [
            'staff' => $staff,
            'institution' => SystemSetting::get('institution_name', 'Devakent Hastanesi'),
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'personeller_' . date('Y-m-d') . '.pdf'
        );
    }

    // ── Query Builder ──
    private function getFilteredQuery()
    {
        return User::query()
            ->role('staff')
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
            });
    }

    public function render()
    {
        $staff = $this->getFilteredQuery()
            ->with('department')
            ->withCount([
                'enrollments',
                'enrollments as completed_enrollments_count' => fn ($q) => $q->where('status', 'completed'),
            ])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $totalStaff = User::role('staff')->count();
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        // Detail modal user — query only when modal is actually open
        $viewingUser = $this->showDetailModal && $this->viewingId
            ? User::with(['department', 'enrollments.course', 'certificates.course'])
                ->withCount([
                    'enrollments',
                    'certificates',
                    'enrollments as completed_count' => fn ($q) => $q->where('status', 'completed'),
                    'enrollments as failed_count' => fn ($q) => $q->where('status', 'failed'),
                    'enrollments as in_progress_count' => fn ($q) => $q->where('status', 'in_progress'),
                ])
                ->find($this->viewingId)
            : null;

        return view('livewire.admin.staff-table', compact('staff', 'departments', 'viewingUser', 'totalStaff'));
    }
}
