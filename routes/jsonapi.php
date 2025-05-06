<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use Sowl\JsonApi\Default\Controller as DefaultController;

Route::group(['as' => config('jsonapi.routing.name', 'jsonapi.')], function () {

    /* ---------------------------------------------------------
    * Add any new custom routes here.
    * --------------------------------------------------------- */

    // User endpoints
    Route::group([
        'prefix' => '/users',
        'as' => 'users.',
    ], function () {
        // User registration without authentication
        Route::post('', [UserController::class, 'create'])
            ->withoutMiddleware('auth')
            ->name('create');

        // Current user profile ("me") endpoint
        Route::get('/me', [UserController::class, 'me'])->name('me');

        // User update endpoint
        Route::patch('/{id}', [UserController::class, 'update'])->name('update');
    });

    /* ---------------------------------------------------------
        * Default routes will be handled by the default controller.
        * --------------------------------------------------------- */
    Route::group(['as' => 'default.'], function () {
        Route::get('/{resourceType}', [DefaultController::class, 'list'])->name('list')->fallback();
        Route::post('/{resourceType}', [DefaultController::class, 'create'])->name('create')->fallback();

        Route::get('/{resourceType}/{id}', [DefaultController::class, 'show'])->name('show')->fallback();
        Route::patch('/{resourceType}/{id}', [DefaultController::class, 'update'])->name('update')->fallback();
        Route::delete('/{resourceType}/{id}', [DefaultController::class, 'remove'])->name('remove')->fallback();

        Route::get('/{resourceType}/{id}/{relationship}', [DefaultController::class, 'showRelated'])->name('showRelated')->fallback();
        Route::get('/{resourceType}/{id}/relationships/{relationship}', [DefaultController::class, 'showRelationships'])->name('showRelationships')->fallback();
        Route::post('/{resourceType}/{id}/relationships/{relationship}', [DefaultController::class, 'createRelationships'])->name('createRelationships')->fallback();
        Route::patch('/{resourceType}/{id}/relationships/{relationship}', [DefaultController::class, 'updateRelationships'])->name('updateRelationships')->fallback();
        Route::delete('/{resourceType}/{id}/relationships/{relationship}', [DefaultController::class, 'removeRelationships'])->name('removeRelationships')->fallback();
    });

});