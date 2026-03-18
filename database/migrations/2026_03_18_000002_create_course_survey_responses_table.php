<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating_overall');    // 1–5
            $table->unsignedTinyInteger('rating_content');    // 1–5
            $table->unsignedTinyInteger('rating_usefulness'); // 1–5
            $table->enum('rating_duration', ['too_short', 'appropriate', 'too_long'])->default('appropriate');
            $table->text('feedback')->nullable();
            $table->unique(['user_id', 'course_id']); // kullanıcı başına 1 yanıt
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_survey_responses');
    }
};
