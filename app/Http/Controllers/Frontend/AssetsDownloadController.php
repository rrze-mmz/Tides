<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Asset;
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
        $path = $asset->disk.'/'.$asset->path;

        $headers = array('Content-Type' => $asset->type);

        return response()->download($path, $asset->original_file_name, $headers);
    }
}
