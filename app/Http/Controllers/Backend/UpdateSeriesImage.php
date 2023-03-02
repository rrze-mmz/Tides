<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Series;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UpdateSeriesImage extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return RedirectResponse
     */
    public function __invoke(Series $series, Request $request)
    {
        $validated = $request->validate([
            'imageID' => ['required', 'exists:images,id'],
            'assignClips' => ['sometimes', 'accepted'],
        ]);

        if (isset($validated['assignClips'])) {
            $series->clips->each(function ($clip) use ($validated) {
                $clip->image_id = $validated['imageID'];
                $clip->save();
            });
        }

        $series->image_id = $validated['imageID'];
        $series->save();

        return to_route('series.edit', $series);
    }
}
