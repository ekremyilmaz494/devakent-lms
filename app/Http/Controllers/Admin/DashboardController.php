<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_staff' => User::role('staff')->count(),
            'active_courses' => Course::where('status', 'published')->count(),
            'completion_rate' => Enrollment::where('status', 'completed')->count()
                ? round(Enrollment::where('status', 'completed')->count() / max(Enrollment::count(), 1) * 100)
                : 0,
            'average_score' => round(Enrollment::whereHas('certificate')->avg('certificate.final_score') ?? 0),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
