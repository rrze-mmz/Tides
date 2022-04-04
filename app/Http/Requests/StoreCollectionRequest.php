<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollectionRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'is_public' => $this->is_public === 'on',
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'position'    => ['required', 'integer'],
            'title'       => ['required', 'string'],
            'description' => ['nullable', 'max:1000'],
            'is_public'   => ['required', 'boolean']
        ];
    }
}
