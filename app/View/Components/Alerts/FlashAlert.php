<?php

namespace App\View\Components\Alerts;

use Illuminate\View\Component;

class FlashAlert extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public string $message)
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
        return view('components.alerts.flash-alert');
    }
}
