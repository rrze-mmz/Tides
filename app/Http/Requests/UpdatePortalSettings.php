<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePortalSettings extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'maintenance_mode' => $this->maintenance_mode === 'on',
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole('superadmin');
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
        ];
    }
}
