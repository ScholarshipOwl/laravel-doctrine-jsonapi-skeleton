<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::prefix(config('jsonapi.routing.rootPathPrefix', ''))
                ->name(config('jsonapi.routing.rootNamePrefix', 'jsonapi.'))
                ->middleware(config('jsonapi.routing.rootMiddleware'))
                ->group(base_path('routes/jsonapi.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group(
            'jsonapi',
            [
                // \Illuminate\Cookie\Middleware\EncryptCookies::class,
                // \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                // \Illuminate\Session\Middleware\StartSession::class,
                // \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                // \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
                // \Illuminate\Routing\Middleware\SubstituteBindings::class,
                // 'auth.session',
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
