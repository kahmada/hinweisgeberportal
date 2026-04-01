<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('report_form');
});

Route::get('/login', function () {
    return view('login');
})->name('login.form')->middleware('guest');

// Anonymous whistleblower tracking routes
Route::get('/track/{token}', [ReportController::class, 'showTrackingLogin'])->name('report.track');
Route::post('/track/login', [ReportController::class, 'trackLogin'])->name('report.track.login');
Route::get('/report/view/{id}', [ReportController::class, 'viewReport'])->name('report.view');
Route::post('/report/logout', [ReportController::class, 'trackLogout'])->name('report.logout');

// Messages for whistleblowers (session-based)
Route::get('/report/{id}/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('report.messages.index');
Route::post('/report/{id}/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('report.messages.store');
Route::post('/report/{id}/messages/mark-read', [\App\Http\Controllers\MessageController::class, 'markAsRead'])->name('report.messages.read');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/reports', function () {
        return view('admin.reports');
    })->name('admin.reports');
});

require __DIR__.'/auth.php';
