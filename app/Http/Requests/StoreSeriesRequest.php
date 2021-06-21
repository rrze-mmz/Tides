<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class StoreSeriesRequest extends FormRequest {
    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->title),
            'acls' => $this->acls = $this->acls ?? [], //set empty array if select2 acls is empty
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create-series', $this->route('series'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'       => 'required',
            'description' => 'max:255',
            'slug'        => 'required',
            'acls'        => 'array',
        ];
    }
}
