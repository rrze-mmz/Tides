<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Clip;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Fetch clips for the home page
     *
     * @return View
     */
    public function __invoke(): View
    {
        return view('frontend.homepage.index',[
            'clips' => Clip::whereHas('assets')->orderByDesc('updated_at')->limit(18)->get(),
        ]);
    }
}
