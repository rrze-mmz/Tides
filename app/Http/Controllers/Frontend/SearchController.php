<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Clip;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    /**
     * Main and basic seach function
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     */
    public function search(SearchRequest $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
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
