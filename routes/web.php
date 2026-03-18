<?php

use App\Http\Controllers\StreamController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Secure video streaming (auth required)
Route::get('/video/{path}', function (string $path) {
    $safePath = 'videos/' . basename($path);

    abort_unless(Storage::disk('local')->exists($safePath), 404);

    return response()->file(Storage::disk('local')->path($safePath));
})->where('path', '[a-zA-Z0-9_\-\.]+')->middleware('auth')->name('video.stream');

// HLS streaming routes
Route::middleware('auth')->group(function () {
    Route::get('/stream/{courseVideo}/playlist.m3u8', [StreamController::class, 'playlist'])->name('stream.playlist');
    Route::get('/stream/{courseVideo}/{filename}', [StreamController::class, 'segment'])->name('stream.segment');
});

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->hasRole('admin')
            ? redirect()->route('admin.dashboard.index')
            : redirect()->route('staff.dashboard.index');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard.index');
    }
    return redirect()->route('staff.dashboard.index');
})->middleware(['auth'])->name('dashboard');

// Dil değiştirme
Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['tr', 'en'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('locale.switch');

require __DIR__.'/auth.php';
