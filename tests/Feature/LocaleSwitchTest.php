<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleSwitchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_locale_switch_to_english(): void
    {
        $response = $this->get('/locale/en');
        $response->assertRedirect();
        $this->assertEquals('en', session('locale'));
    }

    public function test_locale_switch_to_turkish(): void
    {
        $response = $this->get('/locale/tr');
        $response->assertRedirect();
        $this->assertEquals('tr', session('locale'));
    }

    public function test_invalid_locale_ignored(): void
    {
        $response = $this->get('/locale/xx');
        $response->assertRedirect();
        $this->assertNull(session('locale'));
    }

    public function test_sidebar_shows_translated_text(): void
    {
        $dept = Department::create(['name' => 'Yönetim', 'is_active' => true]);
        $admin = User::factory()->create(['department_id' => $dept->id]);
        $admin->assignRole('admin');

        // Switch to English
        $this->actingAs($admin)->get('/locale/en');

        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Learning Management System');
    }
}
