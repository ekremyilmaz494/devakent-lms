<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Sistem Yöneticisi',
            'first_name' => 'Sistem',
            'last_name' => 'Yöneticisi',
            'email' => 'admin@devakent.com',
            'password' => bcrypt(env('ADMIN_DEFAULT_PASSWORD', 'Devakent2024!')),
            'registration_number' => 'DVK-0001',
            'title' => 'Sistem Yöneticisi',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('admin');
    }
}
