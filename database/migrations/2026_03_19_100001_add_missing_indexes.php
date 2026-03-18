<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // course_survey_responses — filtreleme ve unique kontrolü için
        Schema::table('course_survey_responses', function (Blueprint $table) {
            $table->index('course_id', 'survey_responses_course_id_idx');
            $table->index('user_id', 'survey_responses_user_id_idx');
        });

        // questions — kurs bazlı sıralı yükleme için
        Schema::table('questions', function (Blueprint $table) {
            $table->index(['course_id', 'sort_order'], 'questions_course_sort_idx');
        });

        // course_resources — kurs bazlı sıralı yükleme için
        Schema::table('course_resources', function (Blueprint $table) {
            $table->index(['course_id', 'sort_order'], 'course_resources_course_sort_idx');
        });

        // course_videos — kurs bazlı sıralı yükleme için
        Schema::table('course_videos', function (Blueprint $table) {
            $table->index(['course_id', 'sort_order'], 'course_videos_course_sort_idx');
        });
    }

    public function down(): void
    {
        Schema::table('course_survey_responses', function (Blueprint $table) {
            $table->dropIndex('survey_responses_course_id_idx');
            $table->dropIndex('survey_responses_user_id_idx');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex('questions_course_sort_idx');
        });

        Schema::table('course_resources', function (Blueprint $table) {
            $table->dropIndex('course_resources_course_sort_idx');
        });

        Schema::table('course_videos', function (Blueprint $table) {
            $table->dropIndex('course_videos_course_sort_idx');
        });
    }
};
