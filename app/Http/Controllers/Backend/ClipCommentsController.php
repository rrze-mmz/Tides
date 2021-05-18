<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use Illuminate\Http\Request;

class ClipCommentsController extends Controller
{
    public function create(Clip $clip, Request $request)
    {
        $validated = $request->validate([
            'content'   => 'required|min:3',
        ]);

        $validated['owner_id'] = auth()->user()->id;

        $clip->comments()->create($validated);

        return redirect(route('frontend.clips.show', $clip))->with('success_message', 'Comment posted successfully');
    }
}
