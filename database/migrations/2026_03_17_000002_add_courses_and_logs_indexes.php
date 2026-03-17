<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // courses tablosu — filtreleme için
        Schema::table('courses', function (Blueprint $table) {
            $table->index('status', 'courses_status_idx');
            $table->index('category_id', 'courses_category_id_idx');
            $table->index('is_mandatory', 'courses_is_mandatory_idx');
        });

        // activity_log tablosu (Spatie) — ActivityLogViewer filtrelemesi için
        Schema::table('activity_log', function (Blueprint $table) {
            $table->index('created_at', 'activity_log_created_at_idx');
            $table->index(['causer_type', 'causer_id'], 'activity_log_causer_idx');
            $table->index('event', 'activity_log_event_idx');
        });

        // notifications tablosu — bildirim listesi filtrelemesi için
        Schema::table('notifications', function (Blueprint $table) {
            $table->index('created_at', 'notifications_created_at_idx');
            $table->index('target_department_id', 'notifications_target_dept_idx');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('courses_status_idx');
            $table->dropIndex('courses_category_id_idx');
            $table->dropIndex('courses_is_mandatory_idx');
        });

        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropIndex('activity_log_created_at_idx');
            $table->dropIndex('activity_log_causer_idx');
            $table->dropIndex('activity_log_event_idx');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_created_at_idx');
            $table->dropIndex('notifications_target_dept_idx');
        });
    }
};
