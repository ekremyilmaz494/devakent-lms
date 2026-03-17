<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_api_login_returns_token(): void
    {
        $dept = Department::create(['name' => 'Test', 'is_active' => true]);
        $user = User::factory()->create([
            'email' => 'test@devakent.com',
            'password' => 'password',
            'department_id' => $dept->id,
        ]);
        $user->assignRole('staff');

        $response = $this->postJson('/api/login', [
            'email' => 'test@devakent.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token', 'user']);
    }

    public function test_api_login_fails_with_wrong_password(): void
    {
        $dept = Department::create(['name' => 'Test', 'is_active' => true]);
        $user = User::factory()->create([
            'email' => 'test@devakent.com',
            'password' => 'password',
            'department_id' => $dept->id,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@devakent.com',
            'password' => 'wrong-password',
        ]);

        $response->assertUnauthorized();
    }

    public function test_api_me_requires_auth(): void
    {
        $response = $this->getJson('/api/me');
        $response->assertUnauthorized();
    }

    public function test_api_me_returns_user(): void
    {
        $dept = Department::create(['name' => 'Test', 'is_active' => true]);
        $user = User::factory()->create(['department_id' => $dept->id]);
        $user->assignRole('staff');

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->getJson('/api/me', [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertOk()
            ->assertJsonPath('data.id', $user->id);
    }

    public function test_api_logout_revokes_token(): void
    {
        $dept = Department::create(['name' => 'Test', 'is_active' => true]);
        $user = User::factory()->create(['department_id' => $dept->id]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertOk();

        // Token should now be invalid
        $this->getJson('/api/me', [
            'Authorization' => "Bearer {$token}",
        ])->assertUnauthorized();
    }
}
