<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\OpencastService;
use App\Services\OpenSearchService;
use App\Services\WowzaService;
use Illuminate\Contracts\View\View;

class SystemsCheckController extends Controller
{
    /**
     * Get opencast admin node status
     */
    public function __invoke(
        OpencastService $opencastService,
        WowzaService $wowzaService,
        OpenSearchService $openSearchService,
    ): View {
        $opencastStatus = $opencastService->getHealth();
        $wowzaStatus = $wowzaService->getHealth();
        $openSearchStatus = $openSearchService->getHealth();

        //        dd($openSearchStatus);

        return view('backend.systems.status', compact([
            'opencastStatus', 'wowzaStatus', 'openSearchStatus',
        ]));
    }
}
