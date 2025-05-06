<?php

namespace App\Sanctum;

use Illuminate\Auth\RequestGuard;
use Laravel\Sanctum\SanctumServiceProvider as ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Auth;
use LaravelDoctrine\ORM\DoctrineManager;
use App\Sanctum\Entities\PersonalAccessToken;

class SanctumServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->configureSanctumDoctrineAuthProvider();

        $manager = $this->app->make(DoctrineManager::class);
        $manager->addPaths([
            __DIR__ . DIRECTORY_SEPARATOR . 'Entities',
        ]);
    }

    /**
     * Configure Sanctum to use Doctrine as the authentication provider.
     */
    protected function configureSanctumDoctrineAuthProvider(): void
    {
        $doctrineProvider = null;
        foreach (config('auth.providers') as $name => $provider) {
            if ($provider['driver'] === 'doctrine') {
                $doctrineProvider = $name;
                break;
            }
        }

        if ($doctrineProvider !== null) {
            config([
                'auth.guards.sanctum' => array_merge([
                    'provider' => $doctrineProvider,
                ], config('auth.guards.sanctum', [])),
            ]);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }

    protected function configureGuard()
    {
        Auth::resolved(function ($auth) {
            $auth->extend('sanctum', function ($app, $name, array $config) use ($auth) {
                return tap($this->createGuard($auth, $config), function ($guard) {
                    app()->refresh('request', $guard, 'setRequest');
                });
            });
        });
    }

    /**
     * Register the guard.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @param  array  $config
     * @return RequestGuard
     */
    protected function createGuard($auth, $config)
    {
        return new RequestGuard(
            new Guard($auth, config('sanctum.expiration'), $config['provider']),
            request(),
            $auth->createUserProvider($config['provider'] ?? null)
        );
    }
}
