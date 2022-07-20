<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\OpencastService;
use App\Services\WowzaService;
use Illuminate\Contracts\View\View;

class SystemsCheckController extends Controller
{
    /**
     * Get opencast admin node status
     *
     * @param  OpencastService  $opencastService
     * @param  WowzaService  $wowzaService
     * @return View
     */
    public function __invoke(OpencastService $opencastService, WowzaService $wowzaService): View
    {
        $opencastStatus = $opencastService->getHealth();
        $wowzaStatus = $wowzaService->checkApiConnection();

        return view('backend.systems.status', compact([
            'opencastStatus', 'wowzaStatus',
        ]));
    }
}
