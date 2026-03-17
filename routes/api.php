<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\StatsApiController;
use Illuminate\Support\Facades\Route;

// ── Public (Kimlik doğrulamasız) ──
Route::post('/login', [AuthController::class, 'login']);

// ── Authenticated (Sanctum) — yalnızca staff rolü ──
Route::middleware(['auth:sanctum', 'staff'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Eğitimler
    Route::get('/courses', [CourseApiController::class, 'index']);
    Route::get('/courses/{id}', [CourseApiController::class, 'show']);

    // Kayıtlarım
    Route::get('/enrollments', [CourseApiController::class, 'myEnrollments']);
    Route::get('/enrollments/{enrollmentId}', [CourseApiController::class, 'enrollmentDetail']);

    // Dashboard & İstatistikler
    Route::get('/dashboard', [StatsApiController::class, 'dashboard']);
    Route::get('/badges', [StatsApiController::class, 'badges']);
    Route::get('/certificates', [StatsApiController::class, 'certificates']);
});
