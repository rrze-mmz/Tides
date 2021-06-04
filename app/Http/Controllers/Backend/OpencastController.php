<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\OpencastService;
use Illuminate\Contracts\View\View;

class OpencastController extends Controller
{

    /**
     * Get opencast admin node status
     *
     * @param OpencastService $opencastService
     * @return View
     */
    public function __invoke(OpencastService $opencastService): View
    {
        $status = $opencastService->getHealth();

        return view('backend.opencast.status', compact('status'));
    }
}
