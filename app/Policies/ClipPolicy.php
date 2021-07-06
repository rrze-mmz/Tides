<?php


namespace App\Policies;

use App\Models\Clip;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClipPolicy
{

    use HandlesAuthorization;
    /**
     * Check whether the current user can create a clip
     *
     * @return bool
     */
    public function create(): bool
    {
        return auth()->check();
    }

    /**
     * Check whether the given user can edit the given clip
     *
     * @param  User  $user
     * @param  Clip  $clip
     * @return bool
     */
    public function edit(User $user, Clip $clip): bool
    {
        return ($user->is($clip->owner) || $user->hasRole('admin')) ;
    }

    /**
     * @param User|null $user
     * @param Clip $clip
     * @return bool
     */
    public function view(?User $user, Clip $clip): bool
    {
        if (optional($user)->is($clip->owner) || optional($user)->isAdmin()) {
            return true;
        } elseif ($clip->isPublic && (is_null($clip->series->isPublic) || $clip->series->isPublic)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check whether the current user can view the given clip comments
     *
     * @param User|null $user
     * @param Clip $clip
     * @return bool
     */
    public function viewComments(?User $user, Clip $clip): bool
    {
        return (auth()->check() && $clip->allow_comments);
    }

    public function viewVideo(User $user, Clip $clip):bool
    {
        return ((auth()->check() && $clip->acls->pluck('id')->contains('1')) ||
                $user->is($clip->owner));
    }
}
