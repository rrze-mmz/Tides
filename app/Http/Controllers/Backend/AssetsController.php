<?php

namespace App\Http\Controllers\Backend;

use App\Models\Asset;
use App\Models\Clip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssetsController extends Controller
{

    /**
     * Saves a file and persist the asset to the database
     *
     * @param Clip $clip
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function store(Clip $clip, Request $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'uploadedFile' => 'file|required'
        ]);

        $uploadedFile = $request->file('uploadedFile')->store('videos');

        $clip->addAsset($uploadedFile);

        return redirect($clip->adminPath());
    }

    /**
     * @param Asset $asset
     * @return \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Asset $asset): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $this->authorize('edit', $asset);

        $asset->delete();

        return redirect($asset->clip->adminPath());
    }
}
