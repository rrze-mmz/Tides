<?php

namespace App\Policies;

use App\Models\Series;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeriesPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @return bool
     */
    public function create(): bool
    {
        return auth()->check();
    }

    /**
     * @param  User  $user
     * @param  Series  $series
     * @return bool
     */
    public function edit(User $user, Series $series): bool
    {
        return ($user->is($series->owner) || $user->hasRole('admin'));
    }
}
