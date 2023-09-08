<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStreamingSettings extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('administrate-superadmin-portal-pages');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'engine_url' => ['required', 'url'],
            'api_url' => ['required', 'url'],
            'username' => ['required', 'string'],
            'password' => ['required'],
            'content_path' => ['required'],
            'secure_token' => ['required'],
            'token_prefix' => ['required'],
        ];
    }
}
