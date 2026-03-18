<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // questions: question_type ekle, option_c/d ve correct_option nullable yap
        Schema::table('questions', function (Blueprint $table) {
            $table->enum('question_type', ['multiple_choice', 'true_false', 'open_ended'])
                ->default('multiple_choice')
                ->after('course_id');
            $table->string('option_c', 500)->nullable()->change();
            $table->string('option_d', 500)->nullable()->change();
            $table->enum('correct_option', ['a', 'b', 'c', 'd'])->nullable()->change();
        });

        // exam_answers: açık uçlu metin cevabı için text_answer ekle
        Schema::table('exam_answers', function (Blueprint $table) {
            $table->text('text_answer')->nullable()->after('selected_option');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('question_type');
            $table->string('option_c', 500)->nullable(false)->change();
            $table->string('option_d', 500)->nullable(false)->change();
            $table->enum('correct_option', ['a', 'b', 'c', 'd'])->nullable(false)->change();
        });

        Schema::table('exam_answers', function (Blueprint $table) {
            $table->dropColumn('text_answer');
        });
    }
};
