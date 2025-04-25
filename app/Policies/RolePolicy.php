<?php

declare(strict_types=1);

namespace App\Policies;

use App\Entities\Role;
use App\Entities\User;

class RolePolicy
{
    /**
     * Determine whether the user can view any roles.
     */
    public function viewAny(User $user): bool
    {
        // Only admins can view all roles
        return $user->hasRoleByName(Role::ADMIN);
    }

    /**
     * Determine whether the user can view a specific role.
     */
    public function view(User $user, Role $role): bool
    {
        // Only allow admins or add your own logic
        return $user->hasRoleByName(Role::ADMIN);
    }

    /**
     * Determine whether the user can create roles.
     */
    public function create(User $user): bool
    {
        // Only allow admins or add your own logic
        return $user->hasRoleByName(Role::ADMIN);
    }

    /**
     * Determine whether the user can update the role.
     */
    public function update(User $user, Role $role): bool
    {
        // Only allow admins or add your own logic
        return $user->hasRoleByName(Role::ADMIN);
    }

    /**
     * Determine whether the user can delete the role.
     */
    public function delete(User $user, Role $role): bool
    {
        // Only allow admins or add your own logic
        return $user->hasRoleByName(Role::ADMIN);
    }

    /**
     * Determine whether the user can update the users relationship of a role.
     * Only allow admins.
     */
    public function updateUsers(User $user, Role $role): bool
    {
        return $user->hasRoleByName(Role::ADMIN);
    }

    /**
     * Determine whether the user can view any users of the role.
     * Only allow admins.
     */
    public function viewAnyUsers(User $user, Role $role): bool
    {
        return $user->hasRoleByName(Role::ADMIN);
    }

    /**
     * Determine whether the user can attach users to the role.
     * Only allow admins.
     */
    public function attachUsers(User $user, Role $role): bool
    {
        return $user->hasRoleByName(Role::ADMIN);
    }

    /**
     * Determine whether the user can detach users from the role.
     * Only allow admins.
     */
    public function detachUsers(User $user, Role $role): bool
    {
        return $user->hasRoleByName(Role::ADMIN);
    }
}
