<?php

use App\Http\Controllers\Staff\CalendarController;
use App\Http\Controllers\Staff\CertificateController;
use App\Http\Controllers\Staff\CourseController;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\NotificationController;
use App\Http\Controllers\Staff\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');

Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates');
Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

Route::get('/leaderboard', fn () => view('staff.leaderboard'))->name('leaderboard');
Route::get('/badges', fn () => view('staff.badges'))->name('badges');
