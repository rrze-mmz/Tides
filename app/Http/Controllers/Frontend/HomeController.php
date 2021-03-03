<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Clip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home',[
            'clips' => Clip::latest()->limit(18)->get(),
        ]);
    }
}
