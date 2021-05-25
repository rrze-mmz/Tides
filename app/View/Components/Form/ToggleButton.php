<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class ToggleButton extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public bool $value, public string $fieldName)
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
        return view('components.form.toggle-button');
    }
}
