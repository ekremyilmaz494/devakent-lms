<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 100)->after('name');
            $table->string('last_name', 100)->after('first_name');
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('avatar', 255)->nullable()->after('phone');
            $table->string('registration_number', 50)->unique()->nullable()->after('avatar');
            $table->string('title', 100)->nullable()->after('registration_number');
            $table->foreignId('department_id')->nullable()->after('title')->constrained()->nullOnDelete();
            $table->date('hire_date')->nullable()->after('department_id');
            $table->boolean('is_active')->default(true)->after('hire_date');
            $table->json('notification_preferences')->nullable()->after('is_active');
            $table->timestamp('last_login_at')->nullable()->after('notification_preferences');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn([
                'first_name', 'last_name', 'phone', 'avatar',
                'registration_number', 'title', 'department_id',
                'hire_date', 'is_active', 'notification_preferences',
                'last_login_at', 'deleted_at',
            ]);
        });
    }
};
