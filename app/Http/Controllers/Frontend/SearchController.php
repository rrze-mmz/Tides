<?php


namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Clip;
use Illuminate\View\View;

class SearchController extends Controller
{

    /**
     * Main and basic search using ORM
     *
     * @param  SearchRequest  $request
     * @return View
     */
    public function search(SearchRequest $request): Vie
    {
        $clips = Clip::has('assets') // fetch only clips with assets
        ->where(function ($q) use ($request) {
            $q->whereRaw('lower(title)  like (?)', ["%{$request->term}%"])
                ->orWhereRaw('lower(description)  like (?)', ["%{$request->term}%"]);
        }) //search for clip title and description
        ->orWhereHas('owner', function ($q) use ($request) {
            $q->whereRaw('lower(name)  like (?)', ["%{$request->term}%"]);
        }) //search for clip presenter
        ->paginate(10)
        ->withQueryString();

        return view('frontend.search.results', ['clips' => $clips]);
    }
}
