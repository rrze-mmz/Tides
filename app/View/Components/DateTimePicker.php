<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DateTimePicker extends Component
{
    public function __construct(
        public $name,
        public $label,
        public $hasTimeAvailability,
        public $timeAvailabilityStart,
        public $timeAvailabilityEnd
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.date-time-picker');
    }
}
