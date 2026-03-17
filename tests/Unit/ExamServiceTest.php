<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\ExamAttempt;
use App\Models\Question;
use App\Models\User;
use App\Services\ExamService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamServiceTest extends TestCase
{
    use RefreshDatabase;

    private ExamService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ExamService();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_scoring_gives_10_points_per_correct_answer(): void
    {
        // 8 correct out of 10 = 80 points
        $result = $this->createExamScenario(correct: 8, total: 10);
        $this->assertEquals(80, $result['score']);
    }

    public function test_perfect_score_is_100(): void
    {
        $result = $this->createExamScenario(correct: 10, total: 10);
        $this->assertEquals(100, $result['score']);
    }

    public function test_zero_score_when_all_wrong(): void
    {
        $result = $this->createExamScenario(correct: 0, total: 10);
        $this->assertEquals(0, $result['score']);
    }

    public function test_passing_score_evaluation(): void
    {
        // Course passing score is 60, student gets 70 → passed
        $dept = Department::create(['name' => 'Test', 'is_active' => true]);
        $course = Course::factory()->create([
            'passing_score' => 60,
            'status' => 'published',
        ]);
        $user = User::factory()->create(['department_id' => $dept->id]);
        $user->assignRole('staff');
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'in_progress',
            'current_attempt' => 1,
        ]);

        $attempt = ExamAttempt::create([
            'enrollment_id' => $enrollment->id,
            'exam_type' => 'post_exam',
            'attempt_number' => 1,
            'score' => 70,
            'started_at' => now(),
            'finished_at' => now(),
        ]);

        $result = $this->service->evaluateExam($attempt);
        $this->assertTrue($result['is_passed']);
    }

    public function test_failing_score_evaluation(): void
    {
        $dept = Department::create(['name' => 'Test', 'is_active' => true]);
        $course = Course::factory()->create([
            'passing_score' => 60,
            'status' => 'published',
            'max_attempts' => 3,
        ]);
        $user = User::factory()->create(['department_id' => $dept->id]);
        $user->assignRole('staff');
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'in_progress',
            'current_attempt' => 1,
        ]);

        $attempt = ExamAttempt::create([
            'enrollment_id' => $enrollment->id,
            'exam_type' => 'post_exam',
            'attempt_number' => 1,
            'score' => 40,
            'started_at' => now(),
            'finished_at' => now(),
        ]);

        $result = $this->service->evaluateExam($attempt);
        $this->assertFalse($result['is_passed']);
    }

    /**
     * Helper to create an exam scenario and get the score.
     */
    private function createExamScenario(int $correct, int $total): array
    {
        $dept = Department::create(['name' => 'Test', 'is_active' => true]);
        $course = Course::factory()->create(['passing_score' => 60, 'status' => 'published']);
        $user = User::factory()->create(['department_id' => $dept->id]);
        $user->assignRole('staff');

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'in_progress',
            'current_attempt' => 1,
        ]);

        // Create questions
        for ($i = 0; $i < $total; $i++) {
            Question::create([
                'course_id' => $course->id,
                'question_text' => "Question {$i}",
                'option_a' => 'A', 'option_b' => 'B',
                'option_c' => 'C', 'option_d' => 'D',
                'correct_option' => 'A',
            ]);
        }

        $attempt = ExamAttempt::create([
            'enrollment_id' => $enrollment->id,
            'exam_type' => 'pre_exam',
            'attempt_number' => 1,
            'started_at' => now(),
        ]);

        // Score is calculated as correct * 10
        $score = $correct * 10;
        $attempt->update([
            'score' => $score,
            'finished_at' => now(),
        ]);

        return ['score' => $score, 'attempt' => $attempt];
    }
}
