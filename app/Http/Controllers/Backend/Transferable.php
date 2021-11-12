<?php

namespace App\Http\Controllers\Backend;

use App\Jobs\CreateWowzaSmilFile;
use App\Jobs\TransferAssetsJob;
use App\Mail\AssetsTransferred;
use App\Models\Clip;
use App\Services\OpencastService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

trait Transferable
{
    /**
     * @param Clip $clip
     * @param $eventID
     * @param OpencastService $opencastService
     */
    public function checkOpencastAssetsForClipUpload(Clip $clip, $eventID, OpencastService $opencastService)
    {
        $assets = $opencastService->getAssetsByEventID($eventID);

        $deliveryAssets = $assets->filter(function ($value) {
            return Str::contains($value['tag'], 'final');
        });

        $this->uploadAssets($clip, $deliveryAssets, $eventID);
    }

    public function checkDropzoneFilesForClipUpload(Clip $clip, array $validatedFiles)
    {
        $assets = fetchDropZoneFiles()->filter(function ($file, $key) use ($validatedFiles) {
            if (in_array($key, $validatedFiles['files'])) {
                return $file;
            }
        });

        $this->uploadAssets($clip, $assets);
    }

    private function uploadAssets(Clip $clip, Collection $assets, string $eventID = '')
    {
        Bus::chain([
            new TransferAssetsJob($clip, $assets, $eventID),
            new CreateWowzaSmilFile($clip),
        ])->dispatch();

        //mail can be chained via anonymous function inside the bus but then the test  fails
        Mail::to($clip->owner->email)->queue(new AssetsTransferred($clip));
    }
}
