<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\OpencastService;
use Illuminate\Http\Request;

class OpencastController extends Controller
{

    public function __invoke(Request $request, OpencastService $opencastService )
    {
        $status = $opencastService->getHealth();

        return view('backend.opencast.status', compact('status'));
    }
}
