<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\UploadAssetRequest;
use App\Jobs\ConvertVideoForStreaming;
use App\Models\Asset;
use App\Models\Clip;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class AssetsController extends Controller
{

    /**
     * Saves a file and persist the asset to the database
     *
     * @param Clip $clip
     * @param UploadAssetRequest $request
     * @return RedirectResponse
     */
    public function store(Clip $clip, UploadAssetRequest $request): RedirectResponse
    {
        $file = $request->file('asset');

        try{
            $attributes = [
                'disk' => 'videos',
                'original_file_name' => $file->getClientOriginalName(),
                'path'  => $path = $file->store('videos'),
                'duration' => FFMpeg::open($path)->getDurationInSeconds(),
                'width' => FFMpeg::open($path)->getVideoStream()->getDimensions()->getWidth(),
                'height' => FFMpeg::open($path)->getVideoStream()->getDimensions()->getHeight()
            ];

           $asset = $clip->addAsset($attributes);

            //generate a poster image for the clip
            FFMpeg::open($path)
                ->getFrameFromSeconds(5)
                ->export()
                ->toDisk('thumbnails')
                ->save($clip->id.'_poster.png');

            $clip->updatePosterImage();

        }catch (Exception $e)
        {
            Log::error($e);
        }

        $this->dispatch(new ConvertVideoForStreaming($asset));

        return redirect($clip->adminPath());
    }

    /**
     * @param Asset $asset
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(Asset $asset): RedirectResponse
    {
        $this->authorize('edit', $asset);

        $asset->delete();

        $asset->clip->updatePosterImage();

        return redirect($asset->clip->adminPath());
    }
}
