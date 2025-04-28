<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// Web routes have been removed. Only API routes are exposed as per project rules.
Route::get('/', fn() => response('', 200))->name('index');

// JWT Auth endpoints (not JSON:API)
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/request-reset-password', [AuthController::class, 'requestResetPassword'])->name('request-reset-password');
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// JSON:API routes registration
Route::prefix(config('jsonapi.routing.rootPathPrefix', ''))
    ->name(config('jsonapi.routing.rootNamePrefix', 'jsonapi.'))
    ->middleware(config('jsonapi.routing.rootMiddleware'))
    ->group(base_path('routes/jsonapi.php'));

// Scribe routes
Route::group([], base_path('routes/scribe.php'));
