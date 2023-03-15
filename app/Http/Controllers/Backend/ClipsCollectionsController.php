<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClipIdsRequest;
use App\Models\Collection;
use Illuminate\Http\RedirectResponse;

class ClipsCollectionsController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function __invoke(Collection $collection, StoreClipIdsRequest $request)
    {
        $validated = $request->validated();

        $collection->toggleClips(collect($validated['ids']));

        return to_route('collections.edit', $collection);
    }
}
