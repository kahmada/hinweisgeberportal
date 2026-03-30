<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/login', function () {
    return view('login');
})->name('login.form')->middleware('guest');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/reports', function () {
        return view('admin.reports');
    })->name('admin.reports');
});

require __DIR__.'/auth.php';
