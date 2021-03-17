<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Clip;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{

    /**
     * Main and basic seach function
     *
     * @param SearchRequest $request
     * @return View
     */
    public function search(SearchRequest $request): View
    {
        $clips = Clip::has('assets')
                        ->where(function($q) use($request) {
                            $q->whereRaw('lower(title)  like (?)',["%{$request->searchTerm}%"])
                                ->orWhereRaw('lower(description)  like (?)',["%{$request->searchTerm}%"])
                                ->orWhere('owner_id', function($q) use ($request){
                                    $q->select('id')
                                        ->from('users')
                                        ->whereRaw('lower(name)  like (?)',["%{$request->searchTerm}%"]);
                            });
                        })
                        ->get();

        return view('frontend.search.results', ['clips'=> $clips]);
    }
}
