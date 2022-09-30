<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOpencastSettings extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasRole('superadmin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'url' => ['required', 'url'],
            'username' => ['required', 'string'],
            'password' => ['required'],
            'archive_path' => ['required'],
            'default_workflow_id' => ['required', 'string'],
            'upload_workflow_id' => ['required', 'string'],
            'theme_id_top_right' => ['required', 'numeric'],
            'theme_id_top_left' => ['required', 'string'],
            'theme_id_bottom_left' => ['required', 'string'],
            'theme_id_bottom_right' => ['required', 'string'],
        ];
    }
}
