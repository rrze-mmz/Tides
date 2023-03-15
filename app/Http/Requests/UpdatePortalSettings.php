<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePortalSettings extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'maintenance_mode' => $this->maintenance_mode === 'on',
            'allow_user_registration' => $this->allow_user_registration === 'on',
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole(Role::SUPERADMIN);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'maintenance_mode' => ['required', 'boolean'],
            'allow_user_registration' => ['required', 'boolean'],
            'feeds_default_owner_name' => ['required', 'string'],
            'feeds_default_owner_email' => ['required', 'email'],
        ];
    }
}
