<?php

namespace App\Livewire\Staff;

use App\Models\Enrollment;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Leaderboard extends Component
{
    public string $period = 'all'; // all, month, week

    public function render()
    {
        $cacheKey = "leaderboard.{$this->period}";

        $leaderboard = Cache::remember($cacheKey, 300, function () {
            $query = Enrollment::query()
                ->where('status', 'completed')
                ->select('user_id')
                ->selectRaw('COUNT(*) as completed_count')
                ->selectRaw('AVG(enrollments.id) as avg_id'); // placeholder

            if ($this->period === 'month') {
                $query->where('completed_at', '>=', now()->startOfMonth());
            } elseif ($this->period === 'week') {
                $query->where('completed_at', '>=', now()->startOfWeek());
            }

            return $query->groupBy('user_id')
                ->orderByDesc('completed_count')
                ->take(20)
                ->get()
                ->map(function ($row) {
                    $user = User::with('department')->find($row->user_id);
                    if (!$user) return null;

                    $badgeCount = UserBadge::where('user_id', $row->user_id)->count();

                    // Ortalama sınav puanı
                    $avgScore = DB::table('exam_attempts')
                        ->join('enrollments', 'exam_attempts.enrollment_id', '=', 'enrollments.id')
                        ->where('enrollments.user_id', $row->user_id)
                        ->where('exam_attempts.exam_type', 'post_exam')
                        ->where('exam_attempts.is_passed', true)
                        ->avg('exam_attempts.score');

                    return [
                        'user' => $user,
                        'completed_count' => $row->completed_count,
                        'badge_count' => $badgeCount,
                        'avg_score' => round($avgScore ?? 0, 1),
                    ];
                })
                ->filter()
                ->values();
        });

        $currentUserId = auth()->id();
        $currentUserRank = $leaderboard->search(fn ($item) => $item['user']['id'] === $currentUserId);

        return view('livewire.staff.leaderboard', [
            'leaderboard' => $leaderboard,
            'currentUserRank' => $currentUserRank !== false ? $currentUserRank + 1 : null,
        ]);
    }
}
