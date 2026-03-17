<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Kayıtları per-user 5 dk cache'le (her sayfada tüm enrollments yeniden sorgulanmıyordu)
        $enrollments = Cache::remember("staff.dashboard.enrollments.{$user->id}", 300, function () use ($user) {
            return $user->enrollments()
                ->with(['course.category', 'certificate'])
                ->latest()
                ->get();
        });

        $stats = [
            'total'        => $enrollments->count(),
            'completed'    => $enrollments->where('status', 'completed')->count(),
            'in_progress'  => $enrollments->where('status', 'in_progress')->count(),
            'not_started'  => $enrollments->where('status', 'not_started')->count(),
            'failed'       => $enrollments->where('status', 'failed')->count(),
            // Ayrı COUNT sorgusu yerine yüklü ilişkiden say
            'certificates' => $enrollments->filter(fn ($e) => $e->certificate !== null)->count(),
        ];

        $recentEnrollments = $enrollments->take(6);

        return view('staff.dashboard', compact('user', 'enrollments', 'stats', 'recentEnrollments'));
    }
}
