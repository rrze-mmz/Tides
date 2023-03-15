<?php

namespace App\View\Components;

use App\Models\Clip;
use App\Services\WowzaService;
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
    public function __construct(public Clip $clip, public Collection $wowzaStatus, public WowzaService $wowzaService)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.player');
    }
}
