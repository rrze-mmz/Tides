<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class StoreSeriesRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'slug'     => Str::slug($this->title),
            'isPublic' => $this->isPublic === 'on',
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('create-series', $this->route('series'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'           => 'required',
            'description'     => 'max:255',
            'organization_id' => 'required | integer',
            'slug'            => 'required',
            'password'        => ['nullable', Password::min(8)->mixedCase()],
            'isPublic'        => 'boolean',
        ];
    }
}
