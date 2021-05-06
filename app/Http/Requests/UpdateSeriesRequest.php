<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class UpdateSeriesRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $this->merge([
            'slug'  => Str::slug($this->title),
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('edit-series', $this->route('series'));
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
            'description' => 'min:3|max:255',
            'slug'        => 'required',
            'opencast_series_id' => 'null|uuid'
        ];
    }
}
