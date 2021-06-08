<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;


    /**
     * Check whether the given user is an admin and can view a user
     *
     * @param User $user
     * @return mixed
     */
    public function view(User $user): mixed
    {
        return $user->hasRole('admin');
    }
}
