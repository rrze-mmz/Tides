<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Models\Series;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show latest series and clips for the home page
     *
     * @return View
     */
    public function __invoke(): View
    {
        return view('frontend.homepage.index', [
            'series' => Series::isPublic()
                ->hasClipsWithAssets()
                ->orderByDesc('updated_at')
                ->limit(18)
                ->get(),
            'clips' => Clip::public()
                ->whereHas('assets')
                ->whereNull('series_id')
                ->orderByDesc('updated_at')
                ->limit(12)
                ->get(),
        ]);
    }
}
