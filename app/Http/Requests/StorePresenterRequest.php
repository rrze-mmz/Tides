<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePresenterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->isAdmin() || auth()->user()->isAssistant();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'academic_degree_id' => ['integer', 'nullable'],
            'first_name' => ['required', 'alpha', 'min:2', 'max:30'],
            'last_name' => ['required', 'alpha', 'min:2', 'max:100'],
            'username' => ['nullable', 'sometimes', 'alpha_num', 'unique:presenters'],
            'email' => ['nullable', 'sometimes', 'email', 'unique:presenters'],
        ];
    }
}
