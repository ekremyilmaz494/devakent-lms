<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. exam_type enum: 'pre','post' → 'pre_exam','post_exam'
        DB::statement("ALTER TABLE exam_attempts MODIFY COLUMN exam_type ENUM('pre','post','pre_exam','post_exam') NOT NULL");

        // Migrate existing data
        DB::table('exam_attempts')->where('exam_type', 'pre')->update(['exam_type' => 'pre_exam']);
        DB::table('exam_attempts')->where('exam_type', 'post')->update(['exam_type' => 'post_exam']);

        // Remove old values
        DB::statement("ALTER TABLE exam_attempts MODIFY COLUMN exam_type ENUM('pre_exam','post_exam') NOT NULL");

        // 2. courses: start_date and end_date → nullable
        Schema::table('courses', function (Blueprint $table) {
            $table->date('start_date')->nullable()->change();
            $table->date('end_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Revert exam_type
        DB::statement("ALTER TABLE exam_attempts MODIFY COLUMN exam_type ENUM('pre_exam','post_exam','pre','post') NOT NULL");
        DB::table('exam_attempts')->where('exam_type', 'pre_exam')->update(['exam_type' => 'pre']);
        DB::table('exam_attempts')->where('exam_type', 'post_exam')->update(['exam_type' => 'post']);
        DB::statement("ALTER TABLE exam_attempts MODIFY COLUMN exam_type ENUM('pre','post') NOT NULL");

        // Revert dates
        Schema::table('courses', function (Blueprint $table) {
            $table->date('start_date')->nullable(false)->change();
            $table->date('end_date')->nullable(false)->change();
        });
    }
};
