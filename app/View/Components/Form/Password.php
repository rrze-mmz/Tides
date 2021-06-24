<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Password extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $value,
        public string $fieldName,
        public string $label,
        public bool $fullCol = true
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.password');
    }
}
