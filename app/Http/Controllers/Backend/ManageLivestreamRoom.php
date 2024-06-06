<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Livestream;
use App\Models\Series;
use App\Services\OpencastService;
use App\Services\WowzaService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ManageLivestreamRoom extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function reserve(Request $request, OpencastService $opencastService, WowzaService $wowzaService)
    {
        $this->authorize('administrate-portal-pages');
        // Get all inputs
        $inputs = $request->all();

        // Initialize the validation rules array
        $rules = [];

        // Iterate over the inputs and add rules for each location field
        foreach ($inputs as $key => $value) {
            if (str_starts_with($key, 'event_')) {
                $rules[$key] = 'required|uuid';
            }
        }

        // Validate the request
        $validator = Validator::make($inputs, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();

        $eventID = $validated[array_key_first($validated)];
        $event = $opencastService->getEventByEventID($eventID);
        $livestreamClip = null;

        //now check if a livestream clip for this series exists
        $series = Series::where('opencast_series_id', $event['is_part_of'])->first();

        if (! is_null($series)) {
            $livestreamClip = $series->fetchLivestreamClip();
        }
        if (is_null($wowzaService->reserveLivestreamRoom(
            $event['scheduling']['agent_id'],
            $livestreamClip,
            $event['scheduling']['end']
        ))) {
            session()->flash(
                'errorMessage',
                'No livestream room found for Opencast location:'.$event['scheduling']['agent_id']
            );
        }

        return redirect()->back();
    }

    public function cancel(Livestream $livestream)
    {
        $livestream->clip_id = null;
        $livestream->time_availability_end = Carbon::now(); // needs to be calculated properly
        $livestream->active = false;
        $livestream->save();

        return redirect()->back();
    }
}
