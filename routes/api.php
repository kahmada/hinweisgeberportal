<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/reports', [ReportController::class, 'store']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/reports', [ReportController::class, 'index']);
    Route::patch('/reports/{report}', [ReportController::class, 'update']);
});
