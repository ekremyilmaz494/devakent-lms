<?php

namespace Tests\Unit;

use App\Models\Badge;
use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\User;
use App\Services\BadgeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BadgeServiceTest extends TestCase
{
    use RefreshDatabase;

    private BadgeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BadgeService();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_course_completion_badge_awarded(): void
    {
        $dept = Department::create(['name' => 'Test', 'is_active' => true]);
        $user = User::factory()->create(['department_id' => $dept->id]);
        $user->assignRole('staff');

        $badge = Badge::create([
            'name' => 'İlk Adım',
            'slug' => 'ilk-adim',
            'icon' => 'star',
            'color' => '#10B981',
            'type' => 'course_completion',
            'criteria' => ['count' => 1],
            'is_active' => true,
        ]);

        $course = Course::factory()->create(['status' => 'published']);
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'completed',
            'current_attempt' => 1,
        ]);

        $this->service->checkAndAwardBadges($user->id, $enrollment);

        $this->assertDatabaseHas('user_badges', [
            'user_id' => $user->id,
            'badge_id' => $badge->id,
        ]);
    }

    public function test_badge_not_awarded_twice(): void
    {
        $dept = Department::create(['name' => 'Test', 'is_active' => true]);
        $user = User::factory()->create(['department_id' => $dept->id]);
        $user->assignRole('staff');

        $badge = Badge::create([
            'name' => 'İlk Adım',
            'slug' => 'ilk-adim',
            'icon' => 'star',
            'color' => '#10B981',
            'type' => 'course_completion',
            'criteria' => ['count' => 1],
            'is_active' => true,
        ]);

        $course = Course::factory()->create(['status' => 'published']);
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'completed',
            'current_attempt' => 1,
        ]);

        // Award twice
        $this->service->checkAndAwardBadges($user->id, $enrollment);
        $this->service->checkAndAwardBadges($user->id, $enrollment);

        // Should only have 1 record
        $this->assertEquals(1, $user->userBadges()->where('badge_id', $badge->id)->count());
    }

    public function test_inactive_badge_not_awarded(): void
    {
        $dept = Department::create(['name' => 'Test', 'is_active' => true]);
        $user = User::factory()->create(['department_id' => $dept->id]);
        $user->assignRole('staff');

        Badge::create([
            'name' => 'Disabled Badge',
            'slug' => 'disabled',
            'icon' => 'x',
            'color' => '#ccc',
            'type' => 'course_completion',
            'criteria' => ['count' => 1],
            'is_active' => false,
        ]);

        $course = Course::factory()->create(['status' => 'published']);
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'completed',
            'current_attempt' => 1,
        ]);

        $this->service->checkAndAwardBadges($user->id, $enrollment);

        $this->assertDatabaseMissing('user_badges', ['user_id' => $user->id]);
    }
}
