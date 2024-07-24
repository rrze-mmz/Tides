<?php

namespace App\Http\Requests;

use App\Rules\ValidFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UploadPodcastAudioFile extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('edit-podcast', $this->route('podcast'));
    }

    public function rules(): array
    {
        return [
            'asset' => ['required', 'string', new ValidFile(['audio/mpeg'])],
        ];
    }
}
