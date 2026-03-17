<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\ExamAttempt;
use App\Models\UserBadge;

class BadgeService
{
    /**
     * Eğitim tamamlandığında rozet kontrolü yapar ve hak edilen rozetleri atar.
     */
    public function checkAndAwardBadges(int $userId, Enrollment $enrollment): array
    {
        $awarded = [];
        $badges = Badge::where('is_active', true)->get();

        foreach ($badges as $badge) {
            if (!$this->meetsCriterion($badge, $userId, $enrollment)) {
                continue;
            }

            $userBadge = UserBadge::firstOrCreate(
                ['user_id' => $userId, 'badge_id' => $badge->id],
                ['enrollment_id' => $enrollment->id, 'earned_at' => now()]
            );

            if ($userBadge->wasRecentlyCreated) {
                $awarded[] = $badge;
            }
        }

        return $awarded;
    }

    private function meetsCriterion(Badge $badge, int $userId, Enrollment $enrollment): bool
    {
        $criteria = $badge->criteria ?? [];

        return match ($badge->type) {
            'course_completion' => $this->checkCourseCompletion($userId, $criteria),
            'exam_score' => $this->checkExamScore($userId, $enrollment, $criteria),
            'streak' => $this->checkStreak($userId, $criteria),
            'milestone' => $this->checkMilestone($userId, $criteria),
            default => false,
        };
    }

    private function checkCourseCompletion(int $userId, array $criteria): bool
    {
        $requiredCount = $criteria['course_count'] ?? 1;
        $completedCount = Enrollment::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
        return $completedCount >= $requiredCount;
    }

    private function checkExamScore(int $userId, Enrollment $enrollment, array $criteria): bool
    {
        $minScore = $criteria['min_score'] ?? 90;
        $postExam = ExamAttempt::where('enrollment_id', $enrollment->id)
            ->where('exam_type', 'post_exam')
            ->where('is_passed', true)
            ->latest()
            ->first();

        return $postExam && $postExam->score >= $minScore;
    }

    private function checkStreak(int $userId, array $criteria): bool
    {
        $requiredStreak = $criteria['streak_count'] ?? 3;
        // Son X eğitimi üst üste ilk denemede geçmiş mi?
        $recentEnrollments = Enrollment::where('user_id', $userId)
            ->where('status', 'completed')
            ->latest('completed_at')
            ->take($requiredStreak)
            ->get();

        if ($recentEnrollments->count() < $requiredStreak) {
            return false;
        }

        return $recentEnrollments->every(fn ($e) => $e->current_attempt === 1);
    }

    private function checkMilestone(int $userId, array $criteria): bool
    {
        $type = $criteria['milestone_type'] ?? 'certificates';
        $count = $criteria['count'] ?? 5;

        return match ($type) {
            'certificates' => Certificate::where('user_id', $userId)->count() >= $count,
            'perfect_score' => ExamAttempt::whereHas('enrollment', fn ($q) => $q->where('user_id', $userId))
                ->where('exam_type', 'post_exam')
                ->where('score', 100)
                ->count() >= $count,
            default => false,
        };
    }
}
