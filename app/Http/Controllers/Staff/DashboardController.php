<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $enrollments = $user->enrollments()->with('course.category')->latest()->get();

        return view('staff.dashboard', compact('user', 'enrollments'));
    }
}
