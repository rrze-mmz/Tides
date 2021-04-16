<?php


namespace App\Policies;

use App\Models\Clip;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClipPolicy
{

    use HandlesAuthorization;

    /**
     * @return bool
     */
    public function create(): bool
    {
        return auth()->check();
    }

    /**
     * @param  User  $user
     * @param  Clip  $clip
     * @return bool
     */
    public function edit(User $user, Clip $clip): bool
    {
        return ($user->is($clip->owner) || $user->hasRole('admin')) ;
    }
}
