<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;

class UpdateElasticSearchSettings extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasRole(Role::ADMIN);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'url' => ['required', 'string'],
            'port' => ['required', 'integer'],
            'username' => ['required', 'string'],
            'password' => ['required'],
            'prefix' => ['required'],
        ];
    }
}
