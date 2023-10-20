<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->isAdmin() || auth()->user()->isSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title_en' => ['required', 'string', 'max:255'],
            'title_de' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', Rule::unique('articles')],
            'content_en' => ['string', 'max:40000'],
            'content_de' => ['string', 'max:40000'],
            'is_published' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->is_published === 'on',
        ]);
    }
}
