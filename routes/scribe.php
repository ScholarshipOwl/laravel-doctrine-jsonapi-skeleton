<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

// Scribe routes
//
// Allow to use blade variables and functions in the docs
//
$prefix = config('scribe.laravel.docs_url', '/docs');
$middleware = config('scribe.laravel.middleware', []);

Route::middleware($middleware)
    ->group(function () use ($prefix) {
        Route::view($prefix, 'scribe.scribe')->name('scribe');

        Route::get("$prefix.postman", function () {
            $content = Storage::disk('local')->get('scribe/collection.json');
            $content = Blade::render($content, []);

            return response($content, 200)->header('Content-Type', 'application/json');
        })->name('scribe.postman');

        Route::get("$prefix.openapi", function () {
            $openapi = Storage::disk('local')->get('scribe/openapi.yaml');
            $openapi = Blade::render($openapi, []);

            return response($openapi, 200)->header('Content-Type', 'application/yaml');
        })->name('scribe.openapi');
    });
