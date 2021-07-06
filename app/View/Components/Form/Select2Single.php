<?php

namespace App\View\Components\Form;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class Select2Single extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public ?Model $model = null,
        public string $label,
        public string $fieldName,
        public $selectClass,
        public $items,
        public $selectedItem
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
        return view('components.form.select2-single');
    }
}
