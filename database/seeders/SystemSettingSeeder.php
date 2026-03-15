<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'institution_name' => 'Devakent Hastanesi',
            'institution_subtitle' => 'Hastanesi LMS',
            'logo_path' => '/images/logo.png',
            'timezone' => 'Europe/Istanbul',
            'default_language' => 'tr',
            'maintenance_mode' => 'false',
            'default_exam_duration' => '30',
            'default_passing_score' => '70',
            'default_max_attempts' => '3',
            'shuffle_questions' => 'true',
            'session_timeout' => '120',
            'max_login_attempts' => '5',
        ];

        foreach ($settings as $key => $value) {
            SystemSetting::create(['key' => $key, 'value' => $value]);
        }
    }
}
