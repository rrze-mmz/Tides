<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\UploadAssetRequest;
use App\Jobs\ConvertVideoForStreaming;
use App\Mail\VideoUploaded;
use App\Models\Asset;
use App\Models\Clip;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Support\Str;

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

        $clipStoragePath = getClipStoragePath($clip);

        //file name should be a pattern like
        // 20200101-clip-slug.extension
        $savedName  = $file->getClientOriginalName();

        try{
            $attributes = [
                'disk' => 'videos',
                'original_file_name' => $savedName,
                'path'  => $path = $file->storeAs($clipStoragePath, $savedName, 'videos') ,
                'duration' => FFMpeg::fromDisk('videos')->open($path)->getDurationInSeconds(),
                'width' => FFMpeg::fromDisk('videos')->open($path)->getVideoStream()->getDimensions()->getWidth(),
                'height' => FFMpeg::fromDisk('videos')->open($path)->getVideoStream()->getDimensions()->getHeight(),
            ];

           $asset = $clip->addAsset($attributes);

            //generate a poster image for the clip
            FFMpeg::fromDisk('videos')->open($path)
                ->getFrameFromSeconds(5)
                ->export()
                ->toDisk('thumbnails')
                ->save($clip->id.'_poster.png');

            $clip->updatePosterImage();

        }catch (Exception $e)
        {
            Log::error($e);
        }

        if($request->exists('should_convert_to_hls') && $request->should_convert_to_hls)
        {
            $this->dispatch(new ConvertVideoForStreaming($asset));
        }

        Mail::to(auth()->user()->email)
            ->send(new VideoUploaded($clip));

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
