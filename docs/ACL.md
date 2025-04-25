# Role-Based Access Control (RBAC) & ACL in Laravel Doctrine JSON:API Skeleton

This project uses Laravel Doctrine ACL to provide robust Role-Based Access Control (RBAC). This enables you to define roles, assign permissions, and restrict access to resources using policies and middleware, all in a way that is fully compatible with Doctrine entities and Laravel's authorization system.

---

## Table of Contents
- [Overview](#overview)
- [Defining Roles](#defining-roles)
- [Defining Permissions](#defining-permissions)
- [Assigning Roles to Users](#assigning-roles-to-users)
- [Using Policies](#using-policies)
- [Configuration](#configuration)
- [Protecting Routes and Controllers](#protecting-routes-and-controllers)
- [Best Practices](#best-practices)
- [Example Usage](#example-usage)
- [References](#references)

---

## Overview
RBAC allows you to control which users can perform which actions in your application based on their assigned roles and permissions. This skeleton leverages Laravel Doctrine ACL for a flexible, code-driven approach to access control.

- **Roles** represent user groups (e.g., Admin, User, Guest).
- **Permissions** represent specific actions (e.g., create-post, delete-user).
- **Policies** encapsulate authorization logic for resources.

---

## Defining Roles
Roles are defined as Doctrine entities in `app/Entities/Role.php`.

- Add constants for your roles, e.g.:
  ```php
  public const ADMIN = 'admin';
  public const USER = 'user';
  ```
- Roles implement `HasPermissions` and `RoleContract`.
- Use the `WithPermissions` trait for permission management.

---

## Defining Permissions
Permissions are defined as entities in `app/Entities/Permission.php` and configured in `config/acl.php`.

- Add your permissions to the `'list'` array in `config/acl.php`:
  ```php
  'permissions' => [
      'driver' => 'config',
      'entity' => App\Entities\Permission::class,
      'list' => [
          'view-users',
          'edit-users',
          // ...
      ],
  ],
  ```

---

## Assigning Roles to Users
Users implement `HasRoles` and use the `WithRoles` trait (see `app/Entities/User.php`).

- Assign a role to a user:
  ```php
  $user->addRole($role);
  // Or via factory
  $user = entity(App\Entities\User::class)->create();
  $role = entity(App\Entities\Role::class)->create(['name' => 'admin']);
  $user->addRole($role);
  ```
- Check a user's role:
  ```php
  $user->hasRoleByName('admin');
  ```

---

## Using Policies
Authorization logic is encapsulated in policies (see `app/Policies/UserPolicy.php`, `app/Policies/RolePolicy.php`).

- Policies determine if a user can perform a given action on a resource.
- Use the `authorize` method in controllers or actions:
  ```php
  $this->authorize('update', $user); // Uses UserPolicy::update
  ```
- Register policies in `AuthServiceProvider` if needed.

---

## Configuration
The main ACL configuration is in `config/acl.php`:
- Set the entity classes for roles, permissions, and organizations.
- Choose the permission driver (`config` or `doctrine`).

---

## Protecting Routes and Controllers
Use Laravel's built-in authorization middleware:
- `can` middleware for routes:
  ```php
  Route::get('/admin', [AdminController::class, 'index'])->middleware('can:view-admin-dashboard');
  ```
- Policy checks in controllers/actions:
  ```php
  $this->authorize('delete', $user);
  ```

---

## Best Practices
- Define all roles and permissions in code, not in the database.
- Use policies for all resource access checks.
- Keep your ACL configuration (`config/acl.php`) up to date with your entities.
- Use factories for assigning roles in tests and seeds.

---

## Example Usage
Assigning a role in a factory or seeder:
```php
$user = entity(App\Entities\User::class)->create();
$role = entity(App\Entities\Role::class)->create(['name' => 'admin']);
$user->addRole($role);
```

Policy enforcement in a controller:
```php
$this->authorize('update', $user); // Uses UserPolicy
```

Checking permissions:
```php
if ($user->can('edit-users')) {
    // ...
}
```

---

## References
- [app/Entities/Role.php](../app/Entities/Role.php)
- [app/Entities/User.php](../app/Entities/User.php)
- [app/Policies/RolePolicy.php](../app/Policies/RolePolicy.php)
- [app/Policies/UserPolicy.php](../app/Policies/UserPolicy.php)
- [config/acl.php](../config/acl.php)
- [Laravel Doctrine ACL docs](https://github.com/laravel-doctrine/acl)
