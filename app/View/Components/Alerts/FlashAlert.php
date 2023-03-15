<?php

namespace App\View\Components\Alerts;

use Illuminate\View\Component;
use Illuminate\View\View;

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
     */
    public function render(): View
    {
        return view('components.alerts.flash-alert');
    }
}
