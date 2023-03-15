<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\ElasticsearchService;
use App\Services\OpencastService;
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
        ElasticsearchService $elasticsearchService
    ): View {
        $opencastStatus = $opencastService->getHealth();
        $wowzaStatus = $wowzaService->getHealth();
        $elasticsearchStatus = $elasticsearchService->getHealth();

        return view('backend.systems.status', compact([
            'opencastStatus', 'wowzaStatus', 'elasticsearchStatus',
        ]));
    }
}
