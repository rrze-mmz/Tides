<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Clip;
use App\Services\ElasticsearchService;
use Illuminate\View\View;

class ShowSearchResultsController extends Controller
{
    /**
     * Main and basic __invoke using ORM
     */
    public function __invoke(SearchRequest $request, ElasticsearchService $elasticsearchService): View
    {
        $searchResults = collect();
        $health = $elasticsearchService->getHealth();
        //check whether elasticsearch server is up and running
        if ($health->contains('pass')) {
            $results = $elasticsearchService->searchIndexes($request->term);
            $counter = ((float) $health['releaseId']['version']['number'] < 7)
                ? $results['hits']['total']
                : $results['hits']['total']['value'];

            $searchResults = $searchResults->put('clips', $results)->put('counter', $counter);

            return view('frontend.search.results.elasticsearch', compact('searchResults'));
        } else { //use slow db __invoke if no elasticsearch node is found
            $clips = Clip::with('presenters', 'assets')
                ->search($request->term)
                ->whereHas('assets')
                ->orWhereHas('presenters', function ($q) use ($request) {
                    $q->whereRaw('lower(first_name)  like (?)', ["%{$request->term}%"])
                        ->orWhereRaw('lower(last_name)  like (?)', ["%{$request->term}%"]);
                }) //__invoke for clip presenter
                ->orWhereHas('owner', function ($q) use ($request) {
                    $q->whereRaw('lower(first_name)  like (?)', ["%{$request->term}%"])
                        ->orWhereRaw('lower(last_name)  like (?)', ["%{$request->term}%"]);
                }) //__invoke for clip presenter
                ->orderByDesc('recording_date')
                ->paginate(10)
                ->withQueryString();

            $searchResults->put('clips', $clips);

            return view('frontend.search.results.dbsearch', compact('searchResults'));
        }
    }
}
