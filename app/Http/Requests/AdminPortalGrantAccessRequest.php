<?php

namespace App\Http\Requests;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class AdminPortalGrantAccessRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'exists:users,username'],
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (is_null($this->username)) {
                    return false;
                }
                $user = User::search($this->username)->first();
                if (! isset($user->settings->data['admin_portal_application_status'])) {
                    $validator->errors()->add(
                        'username',
                        'user has not applied for admin portal'
                    );

                    return false;
                }

                return true;
            },
        ];
    }
}
