<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class UserPolicy
{
    /**
     * Determine if the user can view admin dashboard.
     */
    public function accessAdmin(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can manage other users.
     */
    public function manageUsers(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can delete their account.
     */
    public function delete(User $user, User $target): bool
    {
        return $user->id === $target->id || $user->isAdmin();
    }
}
