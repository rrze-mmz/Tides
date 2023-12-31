<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Check whether the given user can create a comment
     */
    public function create(User $user): bool
    {
        return auth()->check();
    }

    /**
     * Check whether the given user can delete a comment
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->is($comment->owner) || $user->isAdmin();
    }
}
