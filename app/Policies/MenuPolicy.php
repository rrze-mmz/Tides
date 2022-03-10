<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;


    public function dashboard(User $user): bool
    {
        return $user->isAdmin();
    }
}
