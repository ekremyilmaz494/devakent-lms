<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Dahiliye', 'description' => 'İç Hastalıkları Bölümü'],
            ['name' => 'Cerrahi', 'description' => 'Genel Cerrahi Bölümü'],
            ['name' => 'Acil Servis', 'description' => 'Acil Tıp Bölümü'],
            ['name' => 'Hemşirelik', 'description' => 'Hemşirelik Hizmetleri'],
            ['name' => 'Laboratuvar', 'description' => 'Tıbbi Laboratuvar Bölümü'],
            ['name' => 'Radyoloji', 'description' => 'Görüntüleme Merkezi'],
            ['name' => 'Eczane', 'description' => 'Hastane Eczanesi'],
            ['name' => 'İdari İşler', 'description' => 'İdari ve Mali İşler'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
