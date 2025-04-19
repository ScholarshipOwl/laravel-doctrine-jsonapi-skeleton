<?php

namespace App\Policies;

use App\Entities\User;

class UserPolicy
{
    /**
     * Determine whether the user can create users.
     * Allow anyone (including guests) for JSON:API test/demo.
     */
    public function create(?User $authUser): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view any users (list users).
     * Allow anyone (including guests) for JSON:API test/demo.
     */
    public function viewAny(?User $authUser): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Allow anyone (including guests) to view users for JSON:API test/demo.
     */
    public function view(?User $authUser, User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update users.
     * Only allow users to update themselves (entity comparison).
     */
    public function update(?User $authUser, User $user): bool
    {
        return $authUser !== null && $authUser === $user;
    }

    /**
     * Determine whether the user can delete users.
     * Only allow users to delete themselves (entity comparison).
     */
    public function delete(?User $authUser, User $user): bool
    {
        return $authUser !== null && $authUser === $user;
    }

    // Implement other abilities as needed
}
