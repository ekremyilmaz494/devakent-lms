<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Hijyen', 'color' => '#10B981'],
            ['name' => 'İş Güvenliği', 'color' => '#F59E0B'],
            ['name' => 'Hasta Hakları', 'color' => '#3B82F6'],
            ['name' => 'Enfeksiyon Kontrolü', 'color' => '#EF4444'],
            ['name' => 'İlk Yardım', 'color' => '#EC4899'],
            ['name' => 'Mesleki Gelişim', 'color' => '#8B5CF6'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
