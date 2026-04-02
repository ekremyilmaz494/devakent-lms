<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseExportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\ExamGraderController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
Route::get('/courses/{course}/enrollments', [CourseController::class, 'enrollments'])->name('courses.enrollments');
Route::get('/courses/{course}/export-participants', [CourseExportController::class, 'participants'])->name('courses.export.participants');
Route::get('/courses/{course}/export-results', [CourseExportController::class, 'results'])->name('courses.export.results');

Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
Route::get('/staff/{user}', [StaffController::class, 'show'])->name('staff.show');

Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/exams/grader', [ExamGraderController::class, 'index'])->name('exams.grader');
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
