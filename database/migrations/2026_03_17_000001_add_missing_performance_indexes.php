<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── video_progress: enrollment+video+attempt üçlüsü çok sorgulanan kompozit ──
        Schema::table('video_progress', function (Blueprint $table) {
            $table->index(['enrollment_id', 'attempt_number', 'is_completed'], 'vp_enroll_attempt_completed');
            $table->index(['enrollment_id', 'course_video_id', 'attempt_number'], 'vp_enroll_video_attempt');
        });

        // ── exam_answers: attempt başına cevap sayımı için ──
        if (Schema::hasTable('exam_answers')) {
            Schema::table('exam_answers', function (Blueprint $table) {
                $table->index(['exam_attempt_id', 'is_correct'], 'ea_attempt_correct');
            });
        }

        // ── course_videos: kurs videoları sıralanarak okunuyor ──
        Schema::table('course_videos', function (Blueprint $table) {
            $table->index(['course_id', 'sort_order'], 'cv_course_sort');
        });

        // ── enrollments: completed_at ile zaman bazlı sorgular ──
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index('completed_at');
            $table->index('created_at');
        });

        // ── exam_attempts: zaman bazlı sorgular + finished_at ──
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('finished_at');
            $table->index(['exam_type', 'is_passed'], 'eat_type_passed');
        });
    }

    public function down(): void
    {
        Schema::table('video_progress', function (Blueprint $table) {
            $table->dropIndex('vp_enroll_attempt_completed');
            $table->dropIndex('vp_enroll_video_attempt');
        });

        if (Schema::hasTable('exam_answers')) {
            Schema::table('exam_answers', function (Blueprint $table) {
                $table->dropIndex('ea_attempt_correct');
            });
        }

        Schema::table('course_videos', function (Blueprint $table) {
            $table->dropIndex('cv_course_sort');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex(['completed_at']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['finished_at']);
            $table->dropIndex('eat_type_passed');
        });
    }
};
