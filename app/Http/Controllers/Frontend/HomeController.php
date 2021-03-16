<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Clip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller {


    /**
     * Fetch clips for the home page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): \Illuminate\View\View
    {
        return view('frontend.homepage.index',[
            'clips' => Clip::whereHas('assets')->orderByDesc('updated_at')->limit(18)->get(),
        ]);
    }
}
