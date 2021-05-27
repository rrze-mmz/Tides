<?php

namespace App\View\Components;

use App\Models\Series;
use Illuminate\View\Component;

class ListClips extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public Series $series, public bool $dashboardAction)
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
        return view('components.list-clips');
    }
}
