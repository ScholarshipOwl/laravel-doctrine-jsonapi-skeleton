<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// Web routes have been removed. Only API routes are exposed as per project rules.
Route::get('/', function () {
    return response('', 200);
})->name('index');


// JWT Auth endpoints (not JSON:API)
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/auth/request-reset-password', [AuthController::class, 'requestResetPassword'])->name('auth.request-reset-password');
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth')->name('auth.logout');
