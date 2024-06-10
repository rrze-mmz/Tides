<?php

namespace App\Policies;

use App\Enums\Acl;
use App\Models\Clip;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ClipPolicy
{
    use HandlesAuthorization;

    /**
     * Check whether the current user can view all clips in index
     */
    public function index(User $user): bool
    {
        return auth()->check() && ($user->isAdmin() || $user->isAssistant());
    }

    /**
     * Check whether the current user can create a clip
     */
    public function create(User $user): bool
    {
        return auth()->check() && ($user->isModerator() || $user->isAssistant() || $user->isAdmin());
    }

    /**
     * Check whether the given user can edit the given clip
     */
    public function edit(User $user, Clip $clip): bool
    {
        return $user->is($clip->owner) || ($user->isAdmin() || $user->isAssistant());
    }

    public function view(?User $user, Clip $clip): bool
    {

        if (optional($user)->is($clip->owner) || optional($user)->isAdmin() || optional($user)->isAssistant()) {
            return true;
        } elseif ($clip->is_public &&
            (is_null($clip->series->is_public) || $clip->series->is_public)
            && ($clip->assets()->count() > 0 || $clip->is_livestream)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check whether the current user can view the given clip comments
     */
    public function viewComments(?User $user, Clip $clip): bool
    {
        return auth()->check() && $clip->allow_comments;
    }

    public function viewVideo(User $user, Clip $clip): bool
    {
        return (auth()->check() && $clip->acls->pluck('id')->contains(Acl::PUBLIC())) ||
            ($user->is($clip->owner));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function canWatchVideo(?User $user, Clip $clip): bool
    {
        return $clip->checkAcls();
    }
}
