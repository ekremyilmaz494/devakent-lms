<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Rozet/Badge Tanımları ──
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->string('icon', 50)->default('star'); // SVG icon adı
            $table->string('color', 20)->default('#F59E0B'); // Rozet rengi
            $table->enum('type', ['course_completion', 'exam_score', 'streak', 'milestone', 'special']);
            $table->json('criteria')->nullable(); // {"min_score": 90, "course_count": 5} gibi
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Kullanıcı Rozetleri (Many-to-Many) ──
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('earned_at')->useCurrent();
            $table->unique(['user_id', 'badge_id'], 'user_badge_unique');
        });

        // ── Eğitim Materyalleri/Kaynaklar ──
        Schema::create('course_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title', 200);
            $table->enum('type', ['pdf', 'document', 'link', 'image']);
            $table->string('file_path', 500)->nullable();
            $table->string('url', 500)->nullable();
            $table->unsignedBigInteger('file_size')->nullable(); // bytes
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_resources');
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badges');
    }
};
