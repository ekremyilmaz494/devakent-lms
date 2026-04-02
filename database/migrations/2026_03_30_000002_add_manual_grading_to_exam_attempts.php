<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->boolean('needs_manual_grading')->default(false)->after('is_passed');
            $table->timestamp('manual_grading_completed_at')->nullable()->after('needs_manual_grading');
        });
    }

    public function down(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->dropColumn(['needs_manual_grading', 'manual_grading_completed_at']);
        });
    }
};
