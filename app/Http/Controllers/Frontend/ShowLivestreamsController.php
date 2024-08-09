<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Livestream;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShowLivestreamsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index()
    {
        $livestreams = Livestream::all();
        $livestreams->each(function ($livestream) {
            if ($livestream->app_name === 'test_nasa' && ! $livestream->active) {
                $livestream->active = true;
                $livestream->time_availability_end = Carbon::now()->addDays(300);
                $livestream->save();
            }
        });

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
