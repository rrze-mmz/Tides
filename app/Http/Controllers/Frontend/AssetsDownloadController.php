<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AssetsDownloadController extends Controller
{
    /**
     * Download a given asset
     *
     * @param Asset $asset
     * @return BinaryFileResponse
     */
    public function __invoke(Asset $asset): BinaryFileResponse
    {
        $headers = array('Content-Type' => $asset->type);

        return response()->download(Storage::disk('videos')->path($asset->path), $asset->name, $headers);
    }
}
