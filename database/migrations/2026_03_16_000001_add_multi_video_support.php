<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) course_videos tablosu oluştur
        Schema::create('course_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title', 255);
            $table->string('video_path', 500);
            $table->unsignedInteger('video_duration_seconds')->default(0);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['course_id', 'sort_order']);
        });

        // 2) video_progress tablosuna course_video_id ekle
        Schema::table('video_progress', function (Blueprint $table) {
            $table->foreignId('course_video_id')->nullable()->after('enrollment_id')
                  ->constrained('course_videos')->cascadeOnDelete();
        });

        // 3) Mevcut verileri taşı: courses.video_path → course_videos
        $courses = DB::table('courses')->whereNotNull('video_path')->where('video_path', '!=', '')->get();

        foreach ($courses as $course) {
            $courseVideoId = DB::table('course_videos')->insertGetId([
                'course_id' => $course->id,
                'title' => $course->title . ' - Video',
                'video_path' => $course->video_path,
                'video_duration_seconds' => $course->video_duration_seconds ?? 0,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Bu kursa ait enrollment'ların video_progress kayıtlarını güncelle
            DB::table('video_progress')
                ->whereIn('enrollment_id', function ($query) use ($course) {
                    $query->select('id')
                        ->from('enrollments')
                        ->where('course_id', $course->id);
                })
                ->update(['course_video_id' => $courseVideoId]);
        }

        // 4) Unique constraint güncelle
        // MySQL'de FK bağlı index silinemez — önce FK kaldır, index güncelle, FK geri ekle
        Schema::table('video_progress', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
            $table->dropUnique(['enrollment_id', 'attempt_number']);
            $table->unique(['enrollment_id', 'course_video_id', 'attempt_number'], 'vp_enroll_video_attempt_unique');
            $table->foreign('enrollment_id')->references('id')->on('enrollments')->cascadeOnDelete();
        });

        // 5) courses tablosundan eski video sütunlarını kaldır
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['video_path', 'video_duration_seconds']);
        });
    }

    public function down(): void
    {
        // courses tablosuna eski sütunları geri ekle
        Schema::table('courses', function (Blueprint $table) {
            $table->string('video_path', 500)->nullable()->after('category_id');
            $table->unsignedInteger('video_duration_seconds')->nullable()->after('video_path');
        });

        // Verileri geri taşı
        $courseVideos = DB::table('course_videos')->where('sort_order', 1)->get();
        foreach ($courseVideos as $cv) {
            DB::table('courses')->where('id', $cv->course_id)->update([
                'video_path' => $cv->video_path,
                'video_duration_seconds' => $cv->video_duration_seconds,
            ]);
        }

        // video_progress constraint geri al
        Schema::table('video_progress', function (Blueprint $table) {
            $table->dropUnique('vp_enroll_video_attempt_unique');
            $table->dropForeign(['course_video_id']);
            $table->dropColumn('course_video_id');
            $table->unique(['enrollment_id', 'attempt_number']);
        });

        Schema::dropIfExists('course_videos');
    }
};
