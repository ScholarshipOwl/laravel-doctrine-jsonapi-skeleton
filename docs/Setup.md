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

*This guide highlights only the steps and differences specific to using Doctrine ORM in Laravel. For standard Laravel setup, refer to the official documentation.*
