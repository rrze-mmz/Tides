<?php

namespace App\Http\Requests;

use App\Rules\ValidImageFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StorePodcastRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create-podcasts');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['string', 'max:40000'],
            'hosts' => ['array'],
            'guests' => ['array'],
            'is_published' => ['boolean'],
            'website_url' => ['nullable', 'url'],
            'spotify_url' => ['nullable', 'url'],
            'apple_podcasts_url' => ['nullable', 'url'],
            'image' => ['string', 'nullable', new ValidImageFile(['image/png', 'image/jpeg'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'hosts' => $this->hosts = $this->hosts ?? [],
            'guests' => $this->guests = $this->guests ?? [],
            'is_published' => $this->is_published === 'on',
        ]);
    }
}
