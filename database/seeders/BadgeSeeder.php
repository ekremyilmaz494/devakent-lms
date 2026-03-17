<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'İlk Adım',
                'slug' => 'ilk-adim',
                'description' => 'İlk eğitimini başarıyla tamamla',
                'icon' => 'rocket',
                'color' => '#10B981',
                'type' => 'course_completion',
                'criteria' => ['course_count' => 1],
            ],
            [
                'name' => 'Öğrenme Tutkusu',
                'slug' => 'ogrenme-tutkusu',
                'description' => '5 eğitimi başarıyla tamamla',
                'icon' => 'fire',
                'color' => '#F59E0B',
                'type' => 'course_completion',
                'criteria' => ['course_count' => 5],
            ],
            [
                'name' => 'Bilgi Ustası',
                'slug' => 'bilgi-ustasi',
                'description' => '10 eğitimi başarıyla tamamla',
                'icon' => 'academic-cap',
                'color' => '#8B5CF6',
                'type' => 'course_completion',
                'criteria' => ['course_count' => 10],
            ],
            [
                'name' => 'Mükemmeliyetçi',
                'slug' => 'mukemmeliyetci',
                'description' => 'Bir sınavdan 100 puan al',
                'icon' => 'star',
                'color' => '#EF4444',
                'type' => 'exam_score',
                'criteria' => ['min_score' => 100],
            ],
            [
                'name' => 'Yüksek Başarı',
                'slug' => 'yuksek-basari',
                'description' => 'Bir sınavdan 90+ puan al',
                'icon' => 'trophy',
                'color' => '#3B82F6',
                'type' => 'exam_score',
                'criteria' => ['min_score' => 90],
            ],
            [
                'name' => 'Seri Başarı',
                'slug' => 'seri-basari',
                'description' => '3 eğitimi üst üste ilk denemede geç',
                'icon' => 'lightning-bolt',
                'color' => '#F97316',
                'type' => 'streak',
                'criteria' => ['streak_count' => 3],
            ],
            [
                'name' => 'Sertifika Koleksiyoncusu',
                'slug' => 'sertifika-koleksiyoncusu',
                'description' => '5 sertifika kazan',
                'icon' => 'collection',
                'color' => '#06B6D4',
                'type' => 'milestone',
                'criteria' => ['milestone_type' => 'certificates', 'count' => 5],
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['slug' => $badge['slug']],
                $badge
            );
        }
    }
}
