<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SortIcon extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public $field, public $sortField, public $sortAsc)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.sort-icon');
    }
}
