<?php

namespace App\Policies;

use App\Entities\Role;
use App\Entities\User;

class UserPolicy
{
    /**
     * Determine whether the user can create users.
     * Allow anyone for JSON:API test/demo.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view any users (list users).
     * Allow anyone for JSON:API test/demo.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRoleByName(Role::ADMIN);
    }

    /**
     * Determine whether the user can see a specific user.
     * Allow anyone to view users for JSON:API test/demo.
     */
    public function view(User $user, User $userToSee): bool
    {
        return $user === $userToSee || $user->hasRoleByName(Role::ADMIN);
    }

    /**
     * Determine whether the user can update users.
     * Only allow users to update themselves (entity comparison).
     */
    public function update(User $user, User $userToEdit): bool
    {
        return $user === $userToEdit;
    }

    /**
     * Determine whether the user can delete users.
     * Only allow users to delete themselves (entity comparison).
     */
    public function delete(User $user, User $userToDelete): bool
    {
        return $user === $userToDelete;
    }

    /**
     * Determine whether the user can view any roles of the user.
     * Only allow admins.
     */
    public function viewAnyRoles(User $user, User $ofUser): bool
    {
        if ($user === $ofUser) {
            return true;
        }

        return $user->hasRoleByName(Role::ADMIN);
    }

    /**
     * Determine whether the user can update roles of the user.
     * Only allow admins.
     */
    public function updateRoles(User $user, User $ofUser): bool
    {
        return $user->hasRoleByName(Role::ADMIN);
    }

    /**
     * Determine whether the user can attach roles to the user.
     * Only allow admins.
     */
    public function attachRoles(User $user, User $toUser): bool
    {
        return $user->hasRoleByName(Role::ADMIN);
    }

    /**
     * Determine whether the user can detach roles from the user.
     * Only allow admins.
     */
    public function detachRoles(User $user, User $fromUser): bool
    {
        return $user->hasRoleByName(Role::ADMIN);
    }
}
