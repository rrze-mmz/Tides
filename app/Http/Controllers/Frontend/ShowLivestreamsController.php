<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Livestream;
use Illuminate\Http\Request;

class ShowLivestreamsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('frontend.livestreams.index')->withLivestreams(Livestream::active()->get());
    }
}
