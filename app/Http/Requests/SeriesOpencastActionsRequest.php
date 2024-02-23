<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SeriesOpencastActionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'opencastSeriesID' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== $this->series->opencast_series_id) {
                        return $fail($attribute.' must match the current series opencast series ID.');
                    }
                },
            ],
            'username' => ['sometimes', 'exists:users'],
            'action' => ['sometimes', Rule::in(['addUser', 'removeUser'])],
        ];
    }
}
