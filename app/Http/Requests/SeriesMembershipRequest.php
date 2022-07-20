<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class SeriesMembershipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('delete-series', $this->route('series'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'userID' => ['required', 'integer', Rule::exists('users', 'id'), function ($attibute, $id, $fail) {
                if (! User::find($id)->hasRole('moderator')) {
                    $fail('The given userID does not have a moderator role');
                }
            }],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'id.exists' => 'The user you are inviting must have a tides account',
        ];
    }
}
