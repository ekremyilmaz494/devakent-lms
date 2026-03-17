<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\UserBadge;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatsApiController extends Controller
{
    public function dashboard(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $totalEnrollments = Enrollment::where('user_id', $userId)->count();
        $completed = Enrollment::where('user_id', $userId)->where('status', 'completed')->count();
        $inProgress = Enrollment::where('user_id', $userId)->where('status', 'in_progress')->count();
        $certificates = Certificate::where('user_id', $userId)->count();
        $badges = UserBadge::where('user_id', $userId)->count();

        return response()->json([
            'total_enrollments' => $totalEnrollments,
            'completed' => $completed,
            'in_progress' => $inProgress,
            'certificates' => $certificates,
            'badges' => $badges,
            'completion_rate' => $totalEnrollments > 0 ? round($completed / $totalEnrollments * 100, 1) : 0,
        ]);
    }

    public function badges(Request $request): JsonResponse
    {
        $userBadgeIds = UserBadge::where('user_id', $request->user()->id)->pluck('badge_id');

        $badges = Badge::where('is_active', true)->get()->map(function ($badge) use ($userBadgeIds) {
            return [
                'id' => $badge->id,
                'name' => $badge->name,
                'description' => $badge->description,
                'icon' => $badge->icon,
                'color' => $badge->color,
                'earned' => $userBadgeIds->contains($badge->id),
            ];
        });

        return response()->json($badges);
    }

    public function certificates(Request $request): JsonResponse
    {
        $certificates = Certificate::with('course')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('issued_at')
            ->get();

        return response()->json($certificates);
    }
}
