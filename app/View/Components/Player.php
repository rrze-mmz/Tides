<?php

namespace App\View\Components;

use App\Models\Clip;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Player extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public Clip $clip, public Collection $wowzaStatus)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.player');
    }
}
