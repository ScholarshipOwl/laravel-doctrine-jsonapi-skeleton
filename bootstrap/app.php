<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/jsonapi.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Allow session authentication for API routes
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Always render JSON responses
        $exceptions->shouldRenderJsonWhen(fn () => true);
    })->create();
