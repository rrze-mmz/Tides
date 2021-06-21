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
     * Check whether the current user can view the given clip comments
     *
     * @param User $user
     * @param Clip $clip
     * @return bool
     */
    public function viewComments(User $user, Clip $clip): bool
    {
        return (auth()->check() && $clip->allow_comments);
    }

    public function viewVideo(User $user, Clip $clip):bool
    {

        return (auth()->check() && $clip->acls->pluck('id')->contains('1'));
    }
}
