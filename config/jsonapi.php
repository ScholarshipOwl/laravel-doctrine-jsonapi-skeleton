<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Resource Manager
    |--------------------------------------------------------------------------
    |
    | The entities listed here will be added to ResourceManager and will be
    | used for handling default endpoints. Each entity must implement
    | ResourceInterface.
    |
    */

    'resources' => [
        App\Entities\User::class,
        App\Entities\Role::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Routing configurations
    |--------------------------------------------------------------------------
    */

    'routing' => [

        /**
         * Middleware to apply to all JSON:API routes.
         */
        'rootMiddleware' => ['jsonapi', 'auth'],

        /**
         * Prefix for all the JSON:API route names.
         */
        'rootNamePrefix' => 'jsonapi.',

        /**
         * Prefix for the route path.
         */
        'rootPathPrefix' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Scribe
    |--------------------------------------------------------------------------
    |
    | Configuration of the Scribe package if you are using our strategies.
    |
    */
    'scribe' => [],
];
