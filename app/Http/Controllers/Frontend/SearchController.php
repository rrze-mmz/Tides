<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SearchController extends Controller
{
    public function search(Request $request)
    {

        $clips = Clip::where('title','like','%'.$request->searchTerm.'%')->get();

        return view('frontend.search.results', ['clips'=> $clips]);
    }
}
