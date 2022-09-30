<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserSettings extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'show_subscriptions_to_home_page' => $this->show_subscriptions_to_home_page === 'on',
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'language' => [Rule::in(['en', 'de'])],
            'show_subscriptions_to_home_page' => ['boolean'],
        ];
    }
}
