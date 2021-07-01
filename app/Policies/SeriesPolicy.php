<?php

namespace App\Policies;

use App\Models\Series;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeriesPolicy
{
    use HandlesAuthorization;

    /**
     * Check whether the current user can create a clip.
     *
     * @return bool
     */
    public function create(): bool
    {
        return auth()->check();
    }

    /**
     * @param User|null $user
     * @param Series $series
     * @return bool
     */
    public function view(?User $user, Series $series): bool
    {
        return(
        (!auth()->check() && $series->isPublic)
        || (optional($user)->is($series->owner) || optional($user)->isAdmin())
        );
    }

    /**
     * Check whether the given user can edit the given series
     *
     * @param  User  $user
     * @param  Series  $series
     * @return bool
     */
    public function edit(User $user, Series $series): bool
    {
        return ($user->is($series->owner) || $user->hasRole('admin'));
    }
}
