<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_answers', function (Blueprint $table) {
            $table->decimal('manual_score', 5, 2)->nullable()->after('is_correct');
            $table->text('manual_feedback')->nullable()->after('manual_score');
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete()->after('manual_feedback');
            $table->timestamp('graded_at')->nullable()->after('graded_by');
        });
    }

    public function down(): void
    {
        Schema::table('exam_answers', function (Blueprint $table) {
            $table->dropForeign(['graded_by']);
            $table->dropColumn(['manual_score', 'manual_feedback', 'graded_by', 'graded_at']);
        });
    }
};
