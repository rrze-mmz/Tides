<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSearchSettings extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('administrate-superadmin-portal-pages');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'url' => ['required', 'string'],
            'port' => ['required', 'integer'],
            'username' => ['required', 'string'],
            'password' => ['required'],
            'prefix' => ['required'],
            'search_frontend_enable_open_search' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'search_frontend_enable_open_search' => $this->search_frontend_enable_open_search === 'on',
        ]);
    }
}
