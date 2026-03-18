<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    public function index()
    {
        return view('admin.staff.index');
    }

    public function show(User $user)
    {
        $user->load([
            'department',
            'enrollments.course.category',
            'enrollments.examAttempts',
            'certificates.course',
        ])->loadCount([
            'enrollments',
            'certificates',
            'enrollments as completed_count' => fn ($q) => $q->where('status', 'completed'),
            'enrollments as failed_count' => fn ($q) => $q->where('status', 'failed'),
            'enrollments as in_progress_count' => fn ($q) => $q->where('status', 'in_progress'),
        ]);

        return view('admin.staff.show', compact('user'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('admin.staff.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'email'               => 'required|email|max:255|unique:users,email',
            'password'            => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'phone'               => 'nullable|string|max:20',
            'registration_number' => 'nullable|string|max:50|unique:users,registration_number',
            'title'               => 'nullable|string|max:100',
            'department_id'       => 'required|exists:departments,id',
            'hire_date'           => 'nullable|date',
            'is_active'           => 'boolean',
        ], [
            'first_name.required'          => 'Ad alanı zorunludur.',
            'last_name.required'           => 'Soyad alanı zorunludur.',
            'email.required'               => 'E-posta alanı zorunludur.',
            'email.unique'                 => 'Bu e-posta adresi zaten kullanılıyor.',
            'password.required'            => 'Şifre alanı zorunludur.',
            'password.min'                 => 'Şifre en az 8 karakter, büyük/küçük harf ve rakam içermelidir.',
            'registration_number.unique'   => 'Bu sicil numarası zaten kullanılıyor.',
            'department_id.required'       => 'Departman seçimi zorunludur.',
        ]);

        $user = User::create([
            'first_name'          => $validated['first_name'],
            'last_name'           => $validated['last_name'],
            'name'                => "{$validated['first_name']} {$validated['last_name']}",
            'email'               => $validated['email'],
            'password'            => $validated['password'],
            'phone'               => $validated['phone'] ?? null,
            'registration_number' => $validated['registration_number'] ?? null,
            'title'               => $validated['title'] ?? null,
            'department_id'       => $validated['department_id'],
            'hire_date'           => $validated['hire_date'] ?? null,
            'is_active'           => $request->boolean('is_active', true),
            'email_verified_at'   => now(),
        ]);

        $user->assignRole('staff');

        return redirect()->route('admin.staff.index')
            ->with('success', "{$user->full_name} başarıyla sisteme eklendi.");
    }
}
