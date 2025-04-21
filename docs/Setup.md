# Project Setup Guide

Welcome to the **Laravel Doctrine JSON:API Skeleton** setup guide. This tutorial focuses on the unique steps and differences compared to a typical Laravel project, specifically for setting up Doctrine ORM, migrations, and entities in place of Eloquent.

## 1. Create a New Laravel Project

Start by creating a standard Laravel project:

```bash
composer create-project laravel/laravel laravel-skeleton
cd laravel-skeleton
```

---

## 2. Install and Configure Doctrine ORM (Instead of Eloquent)

Install the Doctrine ORM package for Laravel:

```bash
composer require laravel-doctrine/orm
```

Publish the Doctrine configuration:

```bash
php artisan vendor:publish --provider="LaravelDoctrine\ORM\DoctrineServiceProvider"
```

**Key Differences:**
- Eloquent is not used; all models are replaced by Doctrine entities.
- Doctrine configuration is managed in `config/doctrine.php`.
- Entity classes use Doctrine annotations or attributes for mapping.

---

## 3. Use Doctrine Migrations (Not Laravel Migrations)

Install Doctrine migrations:

```bash
composer require laravel-doctrine/migrations doctrine/dbal
```

Publish the migrations config:

```bash
php artisan vendor:publish --provider="LaravelDoctrine\Migrations\MigrationsServiceProvider"
```

**Key Differences:**
- All database schema changes are managed by Doctrine migrations, not Laravel's default migration system.
- Migration files and configuration are found in `config/migrations.php`.

---

## 4. Enable Doctrine Extensions (Advanced Entity Features)

Install and enable useful Doctrine extensions (timestamps, soft deletes, sluggable, etc.):

```bash
composer require laravel-doctrine/extensions gedmo/doctrine-extensions
```

In `config/doctrine.php`, enable required extensions, for example:

```php
'extensions' => [
    LaravelDoctrine\Extensions\Timestamps\TimestampableExtension::class,
    LaravelDoctrine\Extensions\SoftDeletes\SoftDeleteableExtension::class,
    // ... other extensions
],
```

---

## 5. Replace Eloquent User Model with Doctrine Entity

- Create `app/Entity/User.php` as a Doctrine entity, replacing the default Eloquent model.
- For authentication, we replace the default driver with the Doctrine driver.
- Update `config/auth.php` to use the Doctrine driver and point to your entity class:

```php
'providers' => [
    'users' => [
        'driver' => 'doctrine',
        'model' => \App\Entities\User::class,
    ],
],
```

- Update factories and seeders to use Doctrine's entity system, not Eloquent's.

**Password Reset with Doctrine**

To use Doctrine for password resets, you must replace Laravel’s default `PasswordResetServiceProvider` with `LaravelDoctrine\ORM\Auth\Passwords\PasswordResetServiceProvider`.

**Password Reset with Doctrine (Laravel 12+)**

To use Doctrine for password resets in Laravel 12+, you must replace Laravel’s default `PasswordResetServiceProvider` with `LaravelDoctrine\ORM\Auth\Passwords\PasswordResetServiceProvider`.

In `config/app.php`, use the following approach to override the default provider:

```php
'providers' => ServiceProvider::defaultProviders()->merge([
    App\Providers\AppServiceProvider::class,
])->replace([
    \Illuminate\Auth\Passwords\PasswordResetServiceProvider::class =>
        \LaravelDoctrine\ORM\Auth\Passwords\PasswordResetServiceProvider::class,
])->toArray(),
```

> **Note:** The `providers` key is now required in `config/app.php` for explicit provider customization in Laravel 12 and above. Do not use `bootstrap/providers.php` for this purpose.

Ensure your User entity implements the `Illuminate\Contracts\Auth\CanResetPassword` contract. You can use the `Illuminate\Auth\Passwords\CanResetPassword` trait, which provides the required methods (the trait expects your email property to be named `email`).

For more details and advanced configuration, see the [official Laravel Doctrine password reset documentation](https://laravel-doctrine-orm-official.readthedocs.io/en/latest/passwords.html).

**Key Differences:**
- Entities are plain PHP classes with Doctrine mapping, not Eloquent models.
- Factories and seeders use Doctrine's API.

> For full details on setting up authentication with Laravel Doctrine, refer to the [official Laravel Doctrine authentication documentation](https://laravel-doctrine-orm-official.readthedocs.io/en/latest/auth.html#).

---

## 6. Remove All Web Routes and Blade Views (API-Only)

- Delete `routes/web.php` and all Blade views.
- Only expose API routes as per JSON:API specification.

---

## 7. Doctrine Migrations: What We Changed

All database schema changes in this project are managed using Doctrine migrations, not Laravel's default migration system.

- We configured Doctrine migrations via `config/migrations.php` and removed reliance on Laravel's native migration workflow.
- Whenever we update or add Doctrine entities, we use Doctrine's migration tools to generate and run migrations, ensuring our database schema always matches our entity definitions.
- This approach means we do **not** write migration files by hand for schema changes—instead, Doctrine analyzes the entity mappings and generates the necessary migration code automatically.
- As a result, our schema stays in sync with our codebase, and migrations are fully compatible with Doctrine's advanced features.

For more details, see the official documentation:
- [Laravel Doctrine Migrations Documentation](https://laravel-doctrine-orm.readthedocs.io/en/latest/migrations.html)
- [laravel-doctrine/migrations GitHub Repository](https://github.com/laravel-doctrine/migrations)

### Example: Generating and Running Doctrine Migrations

After you update or create Doctrine entities, you can generate a migration file to reflect your changes in the database using:

```bash
php artisan doctrine:migrations:diff
```

This command analyzes your Doctrine entity mappings and generates a migration file in your migrations directory.

To apply the migration and update your database schema, run:

```bash
php artisan doctrine:migrations:migrate
```

These commands ensure your database schema always matches your Doctrine entities, leveraging the full power of Doctrine's migration system.

---

## 8. Testing with Doctrine: RefreshDatabase, Migrations & Factories

This project replaces Laravel's Eloquent-based test helpers with Doctrine-specific logic for all database testing and seeding. Key setup and usage:

### DoctrineRefreshDatabase Trait
- All test cases use the [DoctrineRefreshDatabase](tests/Traits/DoctrineRefreshDatabase.php) trait
- This trait ensures before each test:
    - Doctrine migrations are run using `$this->artisan('doctrine:migrations:migrate')`
    - A transaction is started on the Doctrine EntityManager
    - The transaction is rolled back after each test for isolation
- No direct use of Laravel's `RefreshDatabase` or `DatabaseTransactions` traits

**Example:**
```php
// tests/TestCase.php
abstract class TestCase extends BaseTestCase
{
    // ...
    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDoctrineDatabase();
    }
    // ...
}
```

### Using Doctrine Entity Factories
- All entity factories are defined in `database/factories` using `LaravelDoctrine\ORM\Testing\Factory`
- Use the global `entity()` helper or `$factory->of()` for generating entities in tests and seeds
- Use `entity(...)->create()` for persisted entities, and `entity(...)->make()` for non-persisted
- Factory definitions must use Doctrine property names (not DB columns)
- Custom factory types are supported via `defineAs`

**Example Usage in Tests:**
```php
$user = entity(App\Entities\User::class)->create();
$users = entity(App\Entities\User::class, 3)->make();
$admin = entity(App\Entities\User::class, 'admin')->create(['name' => 'Alice']);
```

See: [https://laravel-doctrine-orm-official.readthedocs.io/en/latest/testing.html#entity-factories](https://laravel-doctrine-orm-official.readthedocs.io/en/latest/testing.html#entity-factories)

### Example Feature Test
```php
public function testProtectedRouteFails(): void
{
    $user = entity(User::class)->create();
    $this->actingAs($user);

    $response = $this->get('/me');
    $response->assertStatus(200);
    $response->assertJson([
        'name' => $user->getName(),
        'email' => $user->getEmail(),
    ]);
}
```

---

## 9. JSON:API Skeleton Folder Structure and Conventions

This skeleton enforces strict conventions for organizing JSON:API controllers, actions, requests, and responses. This ensures maintainability and consistency across all resources.

### Controller & Action Structure
- Controllers must extend `App\Http\Controllers\Controller`.
- Controllers for each resource type are placed in: `app/Http/Controllers/{ResourceType}/{ControllerName}.php`
- Actions for each resource type are placed in: `app/Http/Controllers/{ResourceType}/{ActionName}.php`
- All actions must extend `Sowl\JsonApi\AbstractAction` (or a subclass).

### Request and Response Classes
- Use `Sowl\JsonApi\Request` for all JSON:API requests.
- Use `Sowl\JsonApi\Response` for all JSON:API responses.
- Custom requests (e.g. for create/update) should be placed in: `app/Http/Controllers/{ResourceType}/{RequestName}.php`

### Example: User Resource
- Controller: `app/Http/Controllers/User/UserController.php`
- Action: `app/Http/Controllers/User/UserMeAction.php`
- Route registration:
  ```php
  use App\Http\Controllers\User\UserController;
  Route::get('/users/me', [UserController::class, 'me']);
  ```

### Why?
- Keeps all resource logic grouped by type for clarity.
- Makes it easy to find, test, and maintain actions and controllers.
- Enforces PSR-12 and project-specific rules for JSON:API compliance.

**Be sure to update this section if you add new resource types or change the folder structure.**

---

---

## 10. JSON:API Validation Testing

### Custom Requests and Actions for Validation

We implement custom request and action classes for each resource to handle validation and business logic in a JSON:API-compliant way:

- **Custom Requests** encapsulate validation rules for create and update endpoints, ensuring all incoming data is validated according to JSON:API and project standards.
- **Custom Actions** (such as [`UserCreateAction`](../app/Http/Controllers/User/UserCreateAction.php) and [`UserUpdateAction`](../app/Http/Controllers/User/UserUpdateAction.php)) are responsible for handling the business logic, updating or creating entities, and returning JSON:API-compliant responses. Actions are thin and reusable, with most logic delegated to services or actions as per windsurf rules.

Below is an example of how validation is implemented for both update and create requests:

#### Example: [`UserCreateRequest`](../app/Http/Controllers/User/UserCreateRequest.php)
```php
use App\Entities\User;
use Sowl\JsonApi\Request;
use Sowl\JsonApi\ResourceRepository;

/**
 * @extends Request<User>
 * @property UserCreateRequest $request
 * @method UserCreateRequest request()
 * @method ResourceRepository<User> repository()
 */
class UserCreateRequest extends Request
{
    public function dataRules(): array
    {
        return [
            'data.type' => ['required', 'in:users'],
            'data.attributes.email' => ['required', 'email', 'unique:users,email'],
            'data.attributes.password' => ['required', 'string', 'min:8'],
            'data.attributes.name' => ['required', 'string', 'max:255'],
        ];
    }
}
```

#### Example: [`UserUpdateRequest`](../app/Http/Controllers/User/UserUpdateRequest.php)
```php
use App\Entities\User;
use Sowl\JsonApi\Request;
use Sowl\JsonApi\ResourceRepository;

/**
 * @extends Request<User>
 * @property UserUpdateRequest $request
 * @method UserUpdateRequest request()
 * @method ResourceRepository<User> repository()
 */
class UserUpdateRequest extends Request
{
    public function dataRules(): array
    {
        return [
            'data.attributes.email' => ['sometimes', 'email', 'max:255'],
            'data.attributes.password' => ['sometimes', 'string', 'min:6'],
            'data.attributes.name' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
```

These custom request classes are used in their respective action classes ([`UserCreateAction`](../app/Http/Controllers/User/UserCreateAction.php), [`UserUpdateAction`](../app/Http/Controllers/User/UserUpdateAction.php)) to validate incoming data for create and update endpoints.

### Custom Validation Assertion

We now use a custom assertion helper for JSON:API validation errors in tests. This ensures that validation error responses conform to the JSON:API specification (with error objects and proper pointers):

```php
/**
 * Assert that the response contains JSON:API validation errors for the given pointers.
 *
 * @param array $pointers Array of JSON pointer strings, e.g., ['/data/attributes/email']
 */
public function assertJsonApiValidationErrors($response, array $pointers): void
{
    $json = $response->json();
    $errors = $json['errors'] ?? [];
    $found = [];
    foreach ($pointers as $pointer) {
        foreach ($errors as $error) {
            if (isset($error['source']['pointer']) && $error['source']['pointer'] === $pointer) {
                $found[] = $pointer;
                break;
            }
        }
    }
    foreach ($pointers as $pointer) {
        $this->assertContains($pointer, $found, "Validation error for pointer '{$pointer}' not found in response.");
    }
}
```

- Use this assertion in your tests instead of Laravel's default `assertJsonValidationErrors`.
- Example usage:

```php
$response->assertStatus(422);
$this->assertJsonApiValidationErrors($response, ['/data/attributes/email']);
```

### Why?
- Ensures strict JSON:API compliance for error responses.
- Makes your tests robust against JSON:API error object structure.
- Required by windsurf project rules.

---

## Keeping the Setup Up-to-Date with Laravel Versions

The setup process described in `Setup.md` is tailored for Laravel 12 and the current state of the Laravel Doctrine integration. **With each new Laravel version, you must review and update the setup process to ensure compatibility and take advantage of new features or changes in the framework.**

- Always check for breaking changes, new conventions, or deprecations in Laravel's release notes.
- Revisit the setup steps, especially those related to service provider registration, authentication, migrations, and any customizations for Doctrine ORM.
- Update `Setup.md` to reflect any new requirements or best practices for the latest Laravel version.
- Clearly state which Laravel version the setup instructions are relevant to at the top of the documentation.

> **Note:** The current setup guide is relevant for Laravel 12. Future contributors should incrementally update the documentation as the project upgrades to newer Laravel versions.
