<?php

namespace App\View\Components\Form;

use App\Models\Acl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Illuminate\View\View;

class Select2Multiple extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public ?Model $model,
        public string $label,
        public string $fieldName,
        public $selectClass,
        public $items = null
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
        $this->items = ($this->items === null) ? Acl::all() : $this->items;

        return view('components.form.select2-multiple');
    }
}
