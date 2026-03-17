<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Users tablosu index'leri ──
        Schema::table('users', function (Blueprint $table) {
            $table->index('department_id');
            $table->index('is_active');
            $table->index('last_login_at');
            $table->index(['is_active', 'department_id']);
        });

        // ── Enrollments tablosu index'leri ──
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index('status');
            $table->index(['user_id', 'status']);
            $table->index(['course_id', 'status']);
            $table->unique(['user_id', 'course_id'], 'enrollments_user_course_unique');
        });

        // ── Exam Attempts tablosu index'leri ──
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->index(['enrollment_id', 'exam_type']);
            $table->index(['enrollment_id', 'attempt_number']);
            $table->index('is_passed');
        });

        // ── Certificates tablosu: user+course unique constraint ──
        Schema::table('certificates', function (Blueprint $table) {
            $table->unique(['user_id', 'course_id'], 'certificates_user_course_unique');
        });

        // ── Notifications tablosu index'leri ──
        Schema::table('notification_recipients', function (Blueprint $table) {
            $table->index(['user_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['department_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['last_login_at']);
            $table->dropIndex(['is_active', 'department_id']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['course_id', 'status']);
            $table->dropUnique('enrollments_user_course_unique');
        });

        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->dropIndex(['enrollment_id', 'exam_type']);
            $table->dropIndex(['enrollment_id', 'attempt_number']);
            $table->dropIndex(['is_passed']);
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropUnique('certificates_user_course_unique');
        });

        Schema::table('notification_recipients', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_read']);
        });
    }
};
