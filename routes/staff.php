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
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
