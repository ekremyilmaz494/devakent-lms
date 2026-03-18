<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // NULL course_video_id içeren orphan satırları temizle
        DB::table('video_progress')->whereNull('course_video_id')->delete();

        // course_video_id sütununu NOT NULL yap
        Schema::table('video_progress', function (Blueprint $table) {
            $table->foreignId('course_video_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('video_progress', function (Blueprint $table) {
            $table->foreignId('course_video_id')->nullable()->change();
        });
    }
};
