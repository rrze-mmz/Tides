<?php

namespace App\View\Components\Form;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class Acl extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public ?Model $model = null)
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
        return view('components.form.acl',[
            'acls'                         => \App\Models\Acl::all(),
        ]);
    }
}
