<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $dept = Department::create(['name' => 'Yönetim', 'is_active' => true]);
        $admin = User::factory()->create(['department_id' => $dept->id]);
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard');
    }

    public function test_staff_cannot_access_admin_dashboard(): void
    {
        $dept = Department::create(['name' => 'Test', 'is_active' => true]);
        $staff = User::factory()->create(['department_id' => $dept->id]);
        $staff->assignRole('staff');

        $response = $this->actingAs($staff)->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_guest_redirected_to_login(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_dashboard_shows_stat_cards(): void
    {
        $dept = Department::create(['name' => 'Yönetim', 'is_active' => true]);
        $admin = User::factory()->create(['department_id' => $dept->id]);
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('weeklyActivityChart');
        $response->assertSee('monthlyTrendChart');
        $response->assertSee('enrollmentStatusChart');
    }
}
