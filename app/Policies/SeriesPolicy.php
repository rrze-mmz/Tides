<?php

namespace App\Policies;

use App\Models\Series;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SeriesPolicy
{
    use HandlesAuthorization;

    /**
     * Check whether the current user can view all series in index
     *
     * @param User $user
     * @return bool
     */
    public function index(User $user): bool
    {
        return (auth()->check() && ($user->isAdmin() || $user->isAssistant()));
    }

    /**
     * Check whether the current user can create a series.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return auth()->check() && ($user->isModerator() || $user->isAdmin() || $user->isAssistant());
    }

    /**
     * @param User|null $user
     * @param Series $series
     * @return Response
     */
    public function view(?User $user, Series $series): Response
    {
        /*
         * return true only for the following:
         * - series is Public and contains clips with assets
         * - user is series owner
         * - user is  admin or superadmin
         */

        return (
            ($series->is_public && $series->clips->filter(fn($clip) => $clip->assets()->count())->count() > 0)
            || (optional($user)->is($series->owner) || optional($user)->isAdmin() || optional($user)->isAssistant())
        )
            ? Response::allow()
            : Response::deny('You do not own this series');
    }

    /**
     * Check whether the given user can edit the given series
     *
     * @param User $user
     * @param Series $series
     * @return Response
     */
    public function edit(User $user, Series $series): Response
    {
        return ($user->is($series->owner) || ($user->isAdmin() || $user->isAssistant()))
            ? Response::allow()
            : Response::deny('You do not own this series');
    }

    /**
     * Check whether the current user can create a series.
     *
     * @param User $user
     * @param Series $series
     * @return Response
     */
    public function update(User $user, Series $series): Response
    {
        //assistants are not allowed to update series info
        return ($user->is($series->owner) || ($user->isAdmin()))
            ? Response::allow()
            : Response::deny('You do not own this series');
    }

    /**
     * Check whether the given user can delete the given series
     *
     * @param User $user
     * @param Series $series
     * @return Response
     */
    public function delete(User $user, Series $series): Response
    {
        return $user->is($series->owner) || $user->isAdmin()
            ? Response::allow()
            : Response::deny('You do not own this series');
    }
}
