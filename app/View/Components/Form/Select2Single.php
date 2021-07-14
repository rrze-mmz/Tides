<?php

namespace App\View\Components\Form;

use App\Models\Semester;
use Illuminate\View\Component;

class Select2Single extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public ?string $model = null,
        public string $label,
        public string $fieldName,
        public $selectClass,
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
        return view('components.form.select2-single', [
                'items' =>  match ($this->model) {
                    'semester' => Semester::where('id', '>', 1)->orderBy('id', 'desc')->get(),
                    'default'  => []
                }
        ]);
    }

    /**
     * Determine if the given option is the currently selected option.
     *
     * @param  string  $option
     * @return bool
     */
    public function isSelected($option)
    {
        return $option === $this->selectedItem;
    }
}
