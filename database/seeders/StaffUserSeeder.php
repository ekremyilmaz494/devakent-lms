<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class StaffUserSeeder extends Seeder
{
    public function run(): void
    {
        $staff = [
            ['first_name' => 'Ayşe', 'last_name' => 'Yılmaz', 'email' => 'ayse@devakent.com', 'registration_number' => 'DVK-1001', 'title' => 'Hemşire', 'department' => 'Hemşirelik'],
            ['first_name' => 'Mehmet', 'last_name' => 'Demir', 'email' => 'mehmet@devakent.com', 'registration_number' => 'DVK-1002', 'title' => 'Dahiliye Uzmanı', 'department' => 'Dahiliye'],
            ['first_name' => 'Fatma', 'last_name' => 'Kaya', 'email' => 'fatma@devakent.com', 'registration_number' => 'DVK-1003', 'title' => 'Laborant', 'department' => 'Laboratuvar'],
            ['first_name' => 'Ali', 'last_name' => 'Çelik', 'email' => 'ali@devakent.com', 'registration_number' => 'DVK-1004', 'title' => 'Acil Tıp Teknisyeni', 'department' => 'Acil Servis'],
            ['first_name' => 'Zeynep', 'last_name' => 'Arslan', 'email' => 'zeynep@devakent.com', 'registration_number' => 'DVK-1005', 'title' => 'Eczacı', 'department' => 'Eczane'],
            ['first_name' => 'Hasan', 'last_name' => 'Öztürk', 'email' => 'hasan@devakent.com', 'registration_number' => 'DVK-1006', 'title' => 'Radyoloji Teknisyeni', 'department' => 'Radyoloji'],
        ];

        foreach ($staff as $s) {
            $dept = Department::firstOrCreate(
                ['name' => $s['department']],
                ['description' => $s['department'] . ' Birimi', 'is_active' => true]
            );

            $user = User::create([
                'name' => $s['first_name'] . ' ' . $s['last_name'],
                'first_name' => $s['first_name'],
                'last_name' => $s['last_name'],
                'email' => $s['email'],
                'password' => bcrypt(env('STAFF_DEFAULT_PASSWORD', 'Staff2024!')),
                'registration_number' => $s['registration_number'],
                'title' => $s['title'],
                'department_id' => $dept->id,
                'hire_date' => now()->subMonths(rand(1, 36)),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $user->assignRole('staff');
        }
    }
}
