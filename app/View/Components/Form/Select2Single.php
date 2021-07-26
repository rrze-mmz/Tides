<?php

namespace App\View\Components\Form;

use App\Models\Organization;
use App\Models\Semester;
use Illuminate\View\Component;
use Illuminate\View\View;

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
     * @return View
     */
    public function render(): View
    {
        return view('components.form.select2-single', [
            'items' => match ($this->model) {
                'semester' => Semester::where('id', '>', 1)
                    ->orderBy('id', 'desc')
                    ->get(),
                'organization' => Organization::select(['org_id as id', 'name'])
                    ->where('org_id', '=', $this->selectedItem)
                    ->get(),//make an api call. Therefore display only the selected option
                'default' => []
            }
        ]);
    }

    /**
     * Determine if the given option is the currently selected option.
     *
     * @param string $option
     * @return bool
     */
    public function isSelected($option): bool
    {
        return $option === $this->selectedItem;
    }
}
