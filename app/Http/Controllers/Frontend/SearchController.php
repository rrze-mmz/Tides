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
     *
     * @param SearchRequest $request
     * @param ElasticsearchService $elasticsearchService
     * @return View
     */
    public function search(SearchRequest $request, ElasticsearchService $elasticsearchService): View
    {
        $searchResults = collect([]);
        //check whether elasticsearch server is up and running
        if ($elasticsearchService->clusterHealth()->isNotEmpty()) {
            $results = $elasticsearchService->searchIndexes($request->term);
            $searchResults = $searchResults->put('clips', $results);
            \Debugbar::info($searchResults);
            return view('frontend.search.results.elasticsearch', compact('searchResults'));
        } else { //use slow db search if no elasticsearch node is found
            $clips = Clip::has('assets') // fetch only clips with assets
            ->where(function ($q) use ($request) {
                $q->whereRaw('lower(title)  like (?)', ["%{$request->term}%"])
                    ->orWhereRaw('lower(description)  like (?)', ["%{$request->term}%"]);
            }) //search for clip title and description
            ->orWhereHas('owner', function ($q) use ($request) {
                $q->whereRaw('lower(first_name)  like (?)', ["%{$request->term}%"])
                    ->orWhereRaw('lower(last_name)  like (?)', ["%{$request->term}%"]);
            }) //search for clip presenter
            ->paginate(10)
                ->withQueryString();
            $searchResults->put('clips', $clips);
            return view('frontend.search.results.dbsearch', compact('searchResults'));
        }
    }
}
