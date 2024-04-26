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
            'wowza_vod_engine_url' => ['required', 'url'],
            'wowza_vod_api_url' => ['required', 'url'],
            'wowza_vod_username' => ['required', 'string'],
            'wowza_vod_password' => ['required'],
            'wowza_vod_content_path' => ['required', 'string'],
            'wowza_vod_secure_token' => ['required', 'string'],
            'wowza_vod_token_prefix' => ['required', 'string'],
            'wowza_livestream_engine_url' => ['required', 'url'],
            'wowza_livestream_api_url' => ['required', 'url'],
            'wowza_livestream_username' => ['required', 'string'],
            'wowza_livestream_password' => ['required'],
            'wowza_livestream_content_path' => ['required', 'string'],
            'wowza_livestream_secure_token' => ['required', 'string'],
            'wowza_livestream_token_prefix' => ['required', 'string'],
            'cdn_server_url' => ['required', 'url'],
            'cdn_server_secret' => ['required'],
        ];
    }
}
