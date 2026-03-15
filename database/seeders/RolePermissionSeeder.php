<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Admin izinleri
        $adminPermissions = [
            'manage-staff',
            'manage-departments',
            'manage-categories',
            'manage-courses',
            'manage-questions',
            'view-reports',
            'manage-notifications',
            'manage-settings',
        ];

        // Staff izinleri
        $staffPermissions = [
            'view-own-courses',
            'take-exams',
            'view-own-certificates',
            'view-own-notifications',
            'manage-own-profile',
        ];

        // Tüm izinleri oluştur
        foreach (array_merge($adminPermissions, $staffPermissions) as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Rolleri oluştur ve izinleri ata
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(array_merge($adminPermissions, $staffPermissions));

        $staffRole = Role::create(['name' => 'staff']);
        $staffRole->givePermissionTo($staffPermissions);
    }
}
