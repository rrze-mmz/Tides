<?php

namespace App\Policies;

use App\Enums\Content;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class AssetPolicy
{
    use HandlesAuthorization;

    /**
     * Check whether the given user can edit the given asset
     */
    public function edit(User $user, Asset $asset): bool
    {
        return $user->is($asset->clips()->first()?->owner) || $user->isAdmin();
    }

    public function download(User $user, Asset $asset): bool
    {
        if ($asset->type === Content::AUDIO() && $asset->podcastEpisodes()->exist()) {
            return true;
        } elseif ($asset->clips()->exists()) {
            $clip = $asset->clips()?->first();

            return Gate::allows('view-clips', $clip);
        } else {
            return false;
        }

    }
}
