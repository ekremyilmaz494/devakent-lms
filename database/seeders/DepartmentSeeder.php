<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Başhekimlik', 'description' => 'Başhekimlik Birimi'],
            ['name' => 'Hemşirelik Hizmetleri', 'description' => 'Hemşirelik Hizmetleri Birimi'],
            ['name' => 'Temizlik ve Destek Hizmetleri', 'description' => 'Temizlik ve Destek Hizmetleri Birimi'],
            ['name' => 'Hasta Hizmetleri', 'description' => 'Hasta Hizmetleri Birimi'],
            ['name' => 'İdari Birim', 'description' => 'İdari ve Mali İşler Birimi'],
            ['name' => 'Diğer Sağlık Personeli', 'description' => 'Diğer Sağlık Personeli Birimi'],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(
                ['name' => $dept['name']],
                $dept
            );
        }
    }
}
