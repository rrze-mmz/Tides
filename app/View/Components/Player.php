<?php

namespace App\View\Components;

use App\Models\Clip;
use App\Services\WowzaService;
use Debugbar;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class Player extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public Clip $clip,
        public Collection $wowzaStatus,
        //        public WowzaService $wowzaService,
        public $defaultVideoUrl
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        //        $urls = $this->wowzaService->vodSecureUrls($this->clip);
        //
        //        if (empty($urls)) {
        //            $defaultPlayerUrl = [];
        //        } elseif ($urls->has('composite')) {
        //            $defaultPlayerUrl = $urls['composite'];
        //        } elseif ($urls->has('presenter')) {
        //            $defaultPlayerUrl = $urls['presenter'];
        //        } elseif ($urls->has('presentation')) {
        //            $defaultPlayerUrl = $urls['presentation'];
        //        } else {
        //            $defaultPlayerUrl = [];
        //        }
        //
        //        Debugbar::info($urls);

        return view('components.player');
    }
}
