<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('thumbnail', 500)->nullable()->after('description');
            $table->boolean('shuffle_questions')->default(false)->after('max_attempts');
            $table->boolean('exam_required')->default(true)->after('shuffle_questions');
            $table->foreignId('prerequisite_course_id')->nullable()->after('exam_required')
                  ->constrained('courses')->nullOnDelete();
            $table->unsignedTinyInteger('repeat_period_months')->nullable()->after('prerequisite_course_id');
            $table->string('language', 10)->default('tr')->after('repeat_period_months');
            $table->string('instructor', 255)->nullable()->after('language');
            $table->json('tags')->nullable()->after('instructor');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['prerequisite_course_id']);
            $table->dropColumn([
                'thumbnail', 'shuffle_questions', 'exam_required',
                'prerequisite_course_id', 'repeat_period_months',
                'language', 'instructor', 'tags',
            ]);
        });
    }
};
