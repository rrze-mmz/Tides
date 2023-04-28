<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Clip;
use App\Services\ElasticsearchService;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Main and basic search using ORM
     */
    public function search(SearchRequest $request, ElasticsearchService $elasticsearchService): View
    {
        $searchResults = collect([]);
        $health = $elasticsearchService->getHealth();
        //check whether elasticsearch server is up and running
        if ($health->contains('pass')) {
            $results = $elasticsearchService->searchIndexes($request->term);
            $counter = ((float) $health['releaseId']['version']['number'] < 7)
                ? $results['hits']['total']
                : $results['hits']['total']['value'];

            $searchResults = $searchResults->put('clips', $results)->put('counter', $counter);

            return view('frontend.search.results.elasticsearch', compact('searchResults'));
        } else { //use slow db search if no elasticsearch node is found
            $clips = Clip::with('presenters')
                ->with('assets')
                ->search($request->term)
                ->whereHas('assets')
                ->orWhereHas('presenters', function ($q) use ($request) {
                    $q->whereRaw('lower(first_name)  like (?)', ["%{$request->term}%"])
                        ->orWhereRaw('lower(last_name)  like (?)', ["%{$request->term}%"]);
                }) //search for clip presenter
                ->orWhereHas('owner', function ($q) use ($request) {
                    $q->whereRaw('lower(first_name)  like (?)', ["%{$request->term}%"])
                        ->orWhereRaw('lower(last_name)  like (?)', ["%{$request->term}%"]);
                }) //search for clip presenter
                ->paginate(10)->withQueryString();
            $searchResults->put('clips', $clips);

            return view('frontend.search.results.dbsearch', compact('searchResults'));
        }
    }
}
