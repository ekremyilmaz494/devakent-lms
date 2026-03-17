<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

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
        return view('admin.staff.create');
    }
}
