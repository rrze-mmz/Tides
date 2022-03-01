<?php

namespace App\View\Components;

use App\Models\Series;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ListClips extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public Series $series,
        public bool $dashboardAction,
        public bool $reorder = false
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.list-clips');
    }
}
