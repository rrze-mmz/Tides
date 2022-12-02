<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Check whether the given user is an admin and can view a user
     *
     * @param  User  $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasRole(Role::ADMIN);
    }

    /**
     * @param  User  $user
     * @return bool
     */
    public function dashboard(User $user): bool
    {
        return $user->isAdmin() || $user->isModerator() || $user->isAssistant();
    }
}
