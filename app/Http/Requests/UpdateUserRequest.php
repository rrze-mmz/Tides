<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'first_name' => ['required', 'alpha', 'min:2', 'max:30'],
            'last_name'  => ['required', 'alpha', 'min:2', 'max:100'],
            'email'      => ['required', Rule::unique('users', 'email')->ignore($this->user->id)],
        ];
    }
}
