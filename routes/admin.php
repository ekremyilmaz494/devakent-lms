<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');

Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');

Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
