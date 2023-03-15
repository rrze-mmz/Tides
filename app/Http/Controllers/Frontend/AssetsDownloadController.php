<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AssetsDownloadController extends Controller
{
    /**
     * Download a given asset
     */
    public function __invoke(Asset $asset): BinaryFileResponse
    {
        return response()->download($asset->downloadPath(), $asset->name, ['Content-Type' => $asset->type]);
    }
}
