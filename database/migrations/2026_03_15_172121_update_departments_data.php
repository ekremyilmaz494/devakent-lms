<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Personele bağlı olmayan eski departmanları sil
        DB::table('departments')
            ->whereNotIn('name', [
                'Başhekimlik',
                'Hemşirelik Hizmetleri',
                'Temizlik ve Destek Hizmetleri',
                'Hasta Hizmetleri',
                'İdari Birim',
                'Diğer Sağlık Personeli',
            ])
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('users')
                    ->whereColumn('users.department_id', 'departments.id');
            })
            ->delete();

        // Yeni departmanları ekle (yoksa)
        $departments = [
            ['name' => 'Başhekimlik', 'description' => 'Başhekimlik Birimi', 'is_active' => true],
            ['name' => 'Hemşirelik Hizmetleri', 'description' => 'Hemşirelik Hizmetleri Birimi', 'is_active' => true],
            ['name' => 'Temizlik ve Destek Hizmetleri', 'description' => 'Temizlik ve Destek Hizmetleri Birimi', 'is_active' => true],
            ['name' => 'Hasta Hizmetleri', 'description' => 'Hasta Hizmetleri Birimi', 'is_active' => true],
            ['name' => 'İdari Birim', 'description' => 'İdari ve Mali İşler Birimi', 'is_active' => true],
            ['name' => 'Diğer Sağlık Personeli', 'description' => 'Diğer Sağlık Personeli Birimi', 'is_active' => true],
        ];

        foreach ($departments as $dept) {
            DB::table('departments')->updateOrInsert(
                ['name' => $dept['name']],
                array_merge($dept, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }

    public function down(): void
    {
        // Geri alma işlemi - eski departmanları geri ekle
    }
};
