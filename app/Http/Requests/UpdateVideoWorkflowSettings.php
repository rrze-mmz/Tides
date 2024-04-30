<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateVideoWorkflowSettings extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'url' => ['required', 'url'],
            'username' => ['required', 'string'],
            'password' => ['required', Password::min(6)],
            'default_workflow_id' => ['required', 'string'],
            'upload_workflow_id' => ['required', 'string'],
            'theme_id_top_right' => ['required', 'numeric'],
            'theme_id_top_left' => ['required', 'numeric'],
            'theme_id_bottom_left' => ['required', 'numeric'],
            'theme_id_bottom_right' => ['required', 'numeric'],
            'archive_path' => ['required', 'string'],
            'assistants_group_name' => ['required', 'string'],
            'opencast_purge_end_date' => ['required', 'date'],
            'opencast_purge_events_per_minute' => ['required', 'numeric'],
        ];
    }
}
