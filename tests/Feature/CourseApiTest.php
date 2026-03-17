<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $dept = Department::create(['name' => 'Test', 'is_active' => true]);
        $this->user = User::factory()->create(['department_id' => $dept->id]);
        $this->user->assignRole('staff');
        $this->token = $this->user->createToken('test')->plainTextToken;
    }

    public function test_list_courses(): void
    {
        Course::factory()->count(3)->create(['status' => 'published']);

        $response = $this->getJson('/api/courses', [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_show_course(): void
    {
        $course = Course::factory()->create(['status' => 'published']);

        $response = $this->getJson("/api/courses/{$course->id}", [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertOk()
            ->assertJsonPath('data.id', $course->id);
    }

    public function test_courses_require_auth(): void
    {
        $response = $this->getJson('/api/courses');
        $response->assertUnauthorized();
    }

    public function test_stats_endpoint(): void
    {
        $response = $this->getJson('/api/stats/dashboard', [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertOk()
            ->assertJsonStructure(['data' => ['total_courses', 'total_enrollments']]);
    }
}
