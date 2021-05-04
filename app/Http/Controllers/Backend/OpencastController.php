<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\OpencastService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class OpencastController extends Controller
{

    public function __invoke(Request $request, OpencastService $opencastService )
    {
        try{
            $status = $opencastService->getHealth();
        } catch (GuzzleException $exception )
        {
            dd($exception);
        }

        return view('backend.opencast.status', compact('status'));
    }
}
