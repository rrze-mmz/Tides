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
    public function index()
    {
        return view('frontend.livestreams.index')->withLivestreams(Livestream::active()->get());
    }

    public function show(Livestream $livestream)
    {
        if ($livestream->clip) {
            return view('frontend.clips.show', $livestream->clip);
        } else {
            return view('backend.livestreams.edit', compact('livestream'));
        }
    }
}
