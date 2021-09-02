<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadAssetRequest;
use App\Jobs\ConvertVideoForStreaming;
use App\Mail\VideoUploaded;
use App\Models\Asset;
use App\Models\Clip;
use FFMpeg\Exception\ExecutableNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AssetsController extends Controller
{
    private Asset $asset;

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

        //file name should be a pattern like
        // 20200101-clip-slug.extension
        $savedName = $file->getClientOriginalName();

        $storedFile = $file->storeAs(getClipStoragePath($clip), $savedName, 'videos');

        $ffmpeg = \FFMpeg::fromDisk('videos')->open($storedFile);

        try {
            $attributes = [
                'disk'               => 'videos',
                'original_file_name' => $savedName,
                'path'               => $storedFile,
                'duration'           => $ffmpeg->getDurationInSeconds(),
                'width'              => $ffmpeg->getVideoStream()->getDimensions()->getWidth(),
                'height'             => $ffmpeg->getVideoStream()->getDimensions()->getHeight(),
                'type'               => 'video',
            ];

            $this->asset = $clip->addAsset($attributes);

            //generate a poster image for the clip
            $ffmpeg->getFrameFromSeconds(5)
                ->export()
                ->toDisk('thumbnails')
                ->save($clip->id . '_poster.png');

            $clip->updatePosterImage();
        } catch (ExecutableNotFoundException $e) {
            Log::error($e);
        }

        if ($request->exists('should_convert_to_hls') && $request->should_convert_to_hls === 'on') {
            $this->dispatch(new ConvertVideoForStreaming($this->asset));
        }

        Mail::to(auth()->user()->email)->send(new VideoUploaded($clip));

        return redirect($clip->adminPath());
    }

    /**
     * Delete the given asset
     *
     * @param Asset $asset
     * @return RedirectResponse
     * @throws Exception
     * @throws AuthorizationException
     */
    public function destroy(Asset $asset): RedirectResponse
    {
        $this->authorize('edit', $asset);

        $asset->delete();

        $asset->clip->updatePosterImage();

        return redirect($asset->clip->adminPath());
    }
}
