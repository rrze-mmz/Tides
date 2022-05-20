<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Document $document
     * @return Response|bool
     */
    public function view(User $user, Document $document)
    {
        //
    }
    
    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Document $document
     * @return Response|bool
     */
    public function delete(User $user, Document $document)
    {
        //
    }
}
