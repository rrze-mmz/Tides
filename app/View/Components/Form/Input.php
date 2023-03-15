<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;
use Illuminate\View\View;

class Input extends Component
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
        public string $inputType,
        public bool $required = false,
        public bool $disabled = false,
        public bool $fullCol = true
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.form.input');
    }
}
