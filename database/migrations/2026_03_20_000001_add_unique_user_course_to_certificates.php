<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Constraint DB'de zaten varsa atla
        $exists = \Illuminate\Support\Facades\DB::select(
            "SHOW INDEX FROM certificates WHERE Key_name = 'certificates_user_course_unique'"
        );

        if (empty($exists)) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->unique(['user_id', 'course_id'], 'certificates_user_course_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropUnique('certificates_user_course_unique');
        });
    }
};
