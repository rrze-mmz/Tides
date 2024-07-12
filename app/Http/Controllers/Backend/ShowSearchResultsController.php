<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Models\Image;
use App\Models\Podcast;
use App\Models\Presenter;
use App\Models\Series;
use App\Models\User;
use Illuminate\Http\Request;

class ShowSearchResultsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $term = $request->input('term'); // assuming the search term is passed as 'q'

        // Extract the ID and determine the redirect URL using match
        return match (true) {
            str_starts_with($term, 's:') => to_route('series.edit', Series::findOrFail(substr($term, 2))),
            str_starts_with($term, 'c:') => to_route('clips.edit', Clip::findOrFail(substr($term, 2))),
            str_starts_with($term, 'p:') => to_route('presenters.edit', Presenter::findOrFail(substr($term, 2))),
            str_starts_with($term, 'i:') => to_route('images.edit', Image::findOrFail(substr($term, 2))),
            str_starts_with($term, 'u:') => to_route('users.edit', User::findOrFail(substr($term, 2))),
            str_starts_with($term, 'pd:') => to_route('podcasts.edit', Podcast::findOrFail(substr($term, 3))),
            default => view('backend.search.index'),
        };
    }
}
