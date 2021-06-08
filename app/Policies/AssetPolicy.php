<?php


namespace App\Policies;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetPolicy
{

    use HandlesAuthorization;

    /**
     * Check whether the given user can edit the given asset
     *
     * @param User $user
     * @param Asset $asset
     * @return bool
     */
    public function edit(User $user, Asset $asset): bool
    {
        return ($user->is($asset->clip->owner) || $user->hasRole('admin'));
    }
}
