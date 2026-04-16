<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/submit', function () {
    return view('report_form');
});

// Language switcher
Route::get('/language/{locale}', function (string $locale) {
    if (in_array($locale, ['de', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');

Route::get('/login', function () {
    return view('login');
})->name('login.form')->middleware('guest');

// User Registration & Login Routes
Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->middleware('guest');
Route::get('/user/login', [\App\Http\Controllers\Auth\UserLoginController::class, 'showLoginForm'])->name('user.login')->middleware('guest');
Route::post('/user/login', [\App\Http\Controllers\Auth\UserLoginController::class, 'login'])->middleware('guest');
Route::post('/user/logout', [\App\Http\Controllers\Auth\UserLoginController::class, 'logout'])->name('user.logout')->middleware('auth');

// Registered User Dashboard Routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports/{id}', [\App\Http\Controllers\User\DashboardController::class, 'show'])->name('reports.show');
});

// Anonymous whistleblower tracking routes
Route::get('/track/{token}', [ReportController::class, 'showTrackingLogin'])->name('report.track');
Route::post('/track/login', [ReportController::class, 'trackLogin'])->name('report.track.login')->middleware('throttle:10,1');
Route::get('/report/view/{id}', [ReportController::class, 'viewReport'])->name('report.view');
Route::post('/report/logout', [ReportController::class, 'trackLogout'])->name('report.logout');

// Messages for whistleblowers (session-based and auth-based)
Route::get('/report/{id}/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('report.messages.index');
Route::post('/report/{id}/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('report.messages.store');
Route::post('/report/{id}/messages/mark-read', [\App\Http\Controllers\MessageController::class, 'markAsRead'])->name('report.messages.read');
Route::get('/attachments/{id}/download', [\App\Http\Controllers\AttachmentController::class, 'download'])->name('attachments.download');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports');
    Route::get('/admin/reports/{id}', [\App\Http\Controllers\Admin\ReportController::class, 'show'])->name('admin.reports.show');
    Route::get('/admin/reports/export/csv', [\App\Http\Controllers\Admin\ExportController::class, 'exportCsv'])->name('admin.reports.export.csv');
    Route::get('/admin/reports/{id}/export', [\App\Http\Controllers\Admin\ExportController::class, 'exportReportHtml'])->name('admin.reports.export.html');
    
    // Admin message routes (web-based)
    Route::get('/admin/reports/{id}/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('admin.reports.messages.index');
    Route::post('/admin/reports/{id}/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('admin.reports.messages.store');
    Route::post('/admin/reports/{id}/messages/mark-read', [\App\Http\Controllers\MessageController::class, 'markAsRead'])->name('admin.reports.messages.read');
    
    // Admin report update routes (web-based)
    Route::patch('/admin/reports/{id}', [ReportController::class, 'update'])->name('admin.reports.update');
    Route::post('/admin/reports/{id}/reveal-identity', [ReportController::class, 'revealIdentity'])->name('admin.reports.reveal');
});

require __DIR__.'/auth.php';
