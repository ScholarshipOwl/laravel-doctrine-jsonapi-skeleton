<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Web routes have been removed. Only API routes are exposed as per project rules.
Route::get('/', function () {
    return response('', 200);
})->name('index');

Route::get('/login', function () {
    return response('', 200);
})->name('login');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/me', function (Request $request) {
        $user = $request->user();
        return response([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ], 200);
    })->name('me');
});
