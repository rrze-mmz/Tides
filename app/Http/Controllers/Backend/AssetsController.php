<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\UploadAssetRequest;
use App\Models\Asset;
use App\Models\Clip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class AssetsController extends Controller
{

    /**
     * Saves a file and persist the asset to the database
     *
     * @param Clip $clip
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function store(Clip $clip, UploadAssetRequest $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $asset = $request->file('asset');

        try{
            $attributes = [
                'disk' => 'videos',
                'original_file_name' => $asset->getClientOriginalName(),
                'path'  => $path = $asset->store('videos'),
                'duration' => FFMpeg::open($path)->getDurationInSeconds(),
                'width' => FFMpeg::open($path)->getVideoStream()->getDimensions()->getWidth(),
                'height' => FFMpeg::open($path)->getVideoStream()->getDimensions()->getHeight()
            ];

            $clip->addAsset($attributes);

            $file = FFMpeg::open($path)
                ->getFrameFromSeconds(5)
                ->export()
                ->toDisk('thumbnails')
                ->save($clip->id.'_poster.png');

            $clip->updatePosterImage();

        }catch (Exception $e)
        {
            Log::error($e);
        }


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
