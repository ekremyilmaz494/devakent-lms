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
                ->join('users', 'enrollments.user_id', '=', 'users.id')
                ->whereNull('users.deleted_at')
                ->where('users.is_active', true)
                ->where('enrollments.status', 'completed')
                ->select('enrollments.user_id')
                ->selectRaw('COUNT(*) as completed_count')
                ->selectRaw('AVG(enrollments.id) as avg_id'); // placeholder

            if ($this->period === 'month') {
                $query->where('enrollments.completed_at', '>=', now()->startOfMonth());
            } elseif ($this->period === 'week') {
                $query->where('enrollments.completed_at', '>=', now()->startOfWeek());
            }

            // Eskisi: N+1 (her kullanıcı için 3 ayrı sorgu × 20 = 60 sorgu)
            // Yenisi: 4 toplu sorgu
            $rows = $query->groupBy('user_id')
                ->orderByDesc('completed_count')
                ->take(20)
                ->get();

            $userIds = $rows->pluck('user_id')->all();

            // Batch: tüm kullanıcılar + departmanlar
            $users = User::with('department')->findMany($userIds)->keyBy('id');

            // Batch: rozet sayıları
            $badgeCounts = UserBadge::whereIn('user_id', $userIds)
                ->select('user_id', DB::raw('COUNT(*) as count'))
                ->groupBy('user_id')
                ->pluck('count', 'user_id');

            // Batch: ortalama sınav puanları
            $avgScores = DB::table('exam_attempts')
                ->join('enrollments', 'exam_attempts.enrollment_id', '=', 'enrollments.id')
                ->whereIn('enrollments.user_id', $userIds)
                ->where('exam_attempts.exam_type', 'post_exam')
                ->where('exam_attempts.is_passed', true)
                ->groupBy('enrollments.user_id')
                ->select('enrollments.user_id', DB::raw('AVG(exam_attempts.score) as avg'))
                ->pluck('avg', 'user_id');

            return $rows->map(function ($row) use ($users, $badgeCounts, $avgScores) {
                    $user = $users->get($row->user_id);
                    if (!$user) return null;
                    return [
                        'user'            => $user,
                        'completed_count' => $row->completed_count,
                        'badge_count'     => $badgeCounts->get($row->user_id, 0),
                        'avg_score'       => round($avgScores->get($row->user_id, 0), 1),
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
