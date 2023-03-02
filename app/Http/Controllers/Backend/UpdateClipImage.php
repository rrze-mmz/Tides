<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UpdateClipImage extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(Clip $clip, Request $request)
    {
        $validated = $request->validate([
            'imageID' => ['required', 'exists:images,id'],
        ]);

        $clip->image_id = $validated['imageID'];
        $clip->save();

        return to_route('clips.edit', $clip);
    }
}
