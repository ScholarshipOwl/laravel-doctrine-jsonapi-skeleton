<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// Web routes have been removed. Only API routes are exposed as per project rules.
Route::get('/', fn () => response('', 200))->name('index');

// JWT Auth endpoints (not JSON:API)
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/request-reset-password', [AuthController::class, 'requestResetPassword'])->name('request-reset-password');
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Scribe routes
Route::group([], base_path('routes/scribe.php'));
