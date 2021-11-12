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
    public function create(User $user): bool
    {
        return auth()->check() && ($user->isModerator() || $user->isAdmin());
    }

    /**
     * @param User|null $user
     * @param Series $series
     * @return bool
     */
    public function view(?User $user, Series $series): bool
    {
        /*
         * return true only if series is Public and contains clips with assets
         * or user is series owner or user is  admin
         */

        return (
            ($series->isPublic && $series->clips->filter(fn($clip) => $clip->assets()->count())->count() > 0)
            || (optional($user)->is($series->owner) || optional($user)->isAdmin())
        );
    }

    /**
     * Check whether the given user can edit the given series
     *
     * @param User $user
     * @param Series $series
     * @return bool
     */
    public function edit(User $user, Series $series): bool
    {
        return ($user->is($series->owner) || $user->hasRole('admin'));
    }
}
