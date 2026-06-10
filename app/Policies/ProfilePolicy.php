<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;

final class ProfilePolicy
{
    /**
     * Determine if the user can view the profile.
     */
    public function view(?User $user, Profile $profile): bool
    {
        return $profile->active || ($user && $user->isAdmin());
    }

    /**
     * Determine if the user can create a profile.
     */
    public function create(User $user): bool
    {
        return $user->profile === null;
    }

    /**
     * Determine if the user can update the profile.
     */
    public function update(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can delete the profile.
     */
    public function delete(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can comment on the profile.
     */
    public function comment(User $user, Profile $profile): bool
    {
        return $profile->active && $user->id !== $profile->user_id;
    }
}
