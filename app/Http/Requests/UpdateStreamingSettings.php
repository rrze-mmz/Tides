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
            'wowza_server1_engine_url' => ['required', 'url'],
            'wowza_server1_api_url' => ['required', 'url'],
            'wowza_server1_api_username' => ['required', 'string'],
            'wowza_server1_api_password' => ['required'],
            'wowza_server1_content_path' => ['required', 'string'],
            'wowza_server1_secure_token' => ['required', 'string'],
            'wowza_server1_token_prefix' => ['required', 'string'],
            'wowza_server2_engine_url' => ['required', 'url'],
            'wowza_server2_api_url' => ['required', 'url'],
            'wowza_server2_api_username' => ['required', 'string'],
            'wowza_server2_api_password' => ['required'],
            'wowza_server2_content_path' => ['required', 'string'],
            'wowza_server2_secure_token' => ['required', 'string'],
            'wowza_server2_token_prefix' => ['required', 'string'],
            'cdn_server1_url' => ['required', 'url'],
            'cdn_server1_secret' => ['required'],
        ];
    }
}
