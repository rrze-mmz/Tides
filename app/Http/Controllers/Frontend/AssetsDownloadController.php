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
        return response()->download($asset->downloadPath(), $asset->name, ['Content-Type' => $asset->type]);
    }
}
