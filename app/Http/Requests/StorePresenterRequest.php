<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StorePresenterRequest extends FormRequest
{
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
            'degree_title' => ['string', 'nullable'],
            'first_name'   => ['required', 'alpha', 'min:2', 'max:30'],
            'last_name'    => ['required', 'alpha', 'min:2', 'max:100'],
            'username'     => ['nullable', 'sometimes', 'alpha_num', 'unique:presenters'],
            'email'        => ['nullable', 'sometimes', 'email', 'unique:presenters'],
        ];
    }
}
