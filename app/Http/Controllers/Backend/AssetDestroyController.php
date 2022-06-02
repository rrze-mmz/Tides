<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;

class AssetDestroyController extends Controller
{
    /**
     * Delete the given asset
     *
     * @param Asset $asset
     * @return RedirectResponse
     * @throws Exception
     * @throws AuthorizationException
     */
    public function __invoke(Asset $asset): RedirectResponse
    {
        $this->authorize('edit', $asset);

        $asset->delete();

        $asset->clip->updatePosterImage();

        return to_route('clips.edit', $asset->clip);
    }
}
