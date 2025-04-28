# Scribe API Documentation Configuration

This project uses [Scribe](https://scribe.knuckles.wtf/) to automatically generate OpenAPI specifications, Postman collections, and static API documentation for all API endpoints.

## Installation

Scribe is already included as a dev dependency. If you need to reinstall, run:

```bash
composer require knuckleswtf/scribe
```

## Publishing Scribe Config

To publish Scribe's configuration and views:

```bash
php artisan vendor:publish --provider="Knuckles\\Scribe\\ScribeServiceProvider"
```

This creates the main config file at `config/scribe.php`.

## JSON:API Language Files

```bash
php artisan vendor:publish --tag=jsonapi-scribe-translations
```

This will publish the language files to your application's `lang/jsonapi` directory, which are used by the Scribe strategies to generate proper documentation text.

## Configuration

### API-only docs
In `config/scribe.php`, ensure API routes are documented:
```php
'routes' => [
    [
        'match' => [
            'prefixes' => ['*'],
            'domains' => ['*'],
        ],
        'exclude' => [
            'GET /', // Exclude root if present
            'GET /up', // Exclude health check route
            'GET /storage', // Exclude storage route
        ],
    ],
],
```

### Docs type and theme
For this skeleton, we recommend and use the `external_laravel` type, which serves the OpenAPI spec with an advanced external UI (Scalar):
```php
'type' => 'external_laravel',
'theme' => 'scalar',
```
This provides a modern, feature-rich OpenAPI UI for your documentation at `/docs` (or the configured docs URL).

### Restricting access to docs
You can restrict access to the API docs by adding middleware (e.g., `['auth']`) in the `laravel.middleware` config section:
```php
'laravel' => [
    ...
    'middleware' => ['auth'], // Only authenticated users can view docs
],
```
You may also use authorization middleware for fine-grained access control.

### JSON:API Scribe strategies
In `config/scribe.php`, add the following to the `strategies` section to enable advanced JSON:API documentation:
```php
'strategies' => [
    'metadata' => [
        ...Defaults::METADATA_STRATEGIES,
        \Sowl\JsonApi\Scribe\Strategies\Metadata\GetFromResourceMetadataAttribute::class,
    ],
    'headers' => [
        ...Defaults::HEADERS_STRATEGIES,
        \Sowl\JsonApi\Scribe\Strategies\Headers\GetFromResourceAttributes::class,
    ],
    'urlParameters' => [
        ...Defaults::URL_PARAMETERS_STRATEGIES,
        \Sowl\JsonApi\Scribe\Strategies\UrlParameters\GetFromResourceRequestAttributes::class,
    ],
    'queryParameters' => [
        ...Defaults::QUERY_PARAMETERS_STRATEGIES,
        \Sowl\JsonApi\Scribe\Strategies\QueryParameters\GetFromResourceRequestAttributes::class,
    ],
    'bodyParameters' => [
        ...Defaults::BODY_PARAMETERS_STRATEGIES,
    ],
    'responses' => configureStrategy(
        [
            ...Defaults::RESPONSES_STRATEGIES,
            JsonApiStrategies\Responses\GetFromResourceResponseAttributes::class,
        ],
        Strategies\Responses\ResponseCalls::withSettings(
            only: ['GET *'],
            // Recommended: disable debug mode in response calls to avoid error stack traces in responses
            config: [
                'app.debug' => false,
            ]
        ),
    ),
    'responseFields' => [
        ...Defaults::RESPONSE_FIELDS_STRATEGIES,
    ]
],
```
**Important:** Keep the response call strategy (as above) to allow Scribe to generate real example responses. The Sowl strategy should be appended after it for enhanced JSON:API support.

### JSON:API OpenAPI spec generator
Add the generator to your config:
```php
'openapi' => [
    ...
    'generators' => [
        \Sowl\JsonApi\Scribe\JsonApiSpecGenerator::class,
    ],
],
```

### Custom route matcher
Set the custom matcher for dynamic route documentation:
```php
'routeMatcher' => \Sowl\JsonApi\Scribe\RouteMatcher::class,
```

### Example resource instantiation strategies
Set the `models_source` option in the `examples` section to use Doctrine strategies for generating example resources:
```php
'examples' => [
    ...
    'models_source' => [
        'doctrineFactoryCreate',
        // 'doctrineFactoryMake', // Optionally enable for non-persisted entities
        'doctrineRepositoryFirst',
    ],
],
```
This ensures Scribe will use Doctrine factories and repositories to generate example entities for your API docs. Uncomment `doctrineFactoryMake` if you want to allow non-persisted examples.

### Default group for endpoints
Set the default group to `null` so the library can automatically assign group names for endpoints without a `@group`:
```php
'groups' => [
    'default' => null,
    // ...
],
```
This allows Scribe to automatically group endpoints based on their controllers or other logic, making it easier to organize your API documentation.

## Generating Documentation

To generate or update the API documentation, run:

```bash
php artisan scribe:generate
```

For more advanced customization, see the [Scribe config reference](https://scribe.knuckles.wtf/laravel/reference/config) and the [laravel-doctrine-jsonapi docs](https://github.com/ScholarshipOwl/laravel-doctrine-jsonapi).

## Further Reading
- [Scribe Documentation](https://scribe.knuckles.wtf/)
- [Scribe Config Reference](https://scribe.knuckles.wtf/laravel/reference/config)
