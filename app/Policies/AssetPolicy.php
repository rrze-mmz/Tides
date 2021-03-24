<?php


namespace App\Policies;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetPolicy
{

    use HandlesAuthorization;

    public function edit(User $user, Asset $asset): bool
    {
        return $user->is($asset->clip->owner);
    }
}
