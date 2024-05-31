<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Services\WowzaService;
use Illuminate\Http\Request;

class ReserveLivestreamRoom extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, WowzaService $wowzaService)
    {
        $this->authorize('administrate-portal-pages');
        $validated = $request->validate([
            'location' => ['required', 'string', 'exists:livestreams,name'],
        ]);

        $location = $validated['location'];

        $clip = Clip::find(23799);
        $wowzaService->reserveLivestreamRoom($clip, $location);

        return redirect()->back();
    }
}
