<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Certificate;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $enrollments = $user->enrollments()
            ->with(['course.category', 'certificate'])
            ->latest()
            ->get();

        $stats = [
            'total' => $enrollments->count(),
            'completed' => $enrollments->where('status', 'completed')->count(),
            'in_progress' => $enrollments->where('status', 'in_progress')->count(),
            'not_started' => $enrollments->where('status', 'not_started')->count(),
            'failed' => $enrollments->where('status', 'failed')->count(),
            'certificates' => Certificate::where('user_id', $user->id)->count(),
        ];

        $recentEnrollments = $enrollments->take(6);

        return view('staff.dashboard', compact('user', 'enrollments', 'stats', 'recentEnrollments'));
    }
}
