<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;
use Illuminate\View\View;

class ToggleButton extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public bool $value,
        public string $label,
        public string $fieldName,
        public string $labelClass = 'col-span-2',
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
        return view('components.form.toggle-button');
    }
}
