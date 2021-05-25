<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class StoreClipRequest extends FormRequest
{

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->title),
            'tags' => $this->tags = $this->tags ?? [], //set empty array if select2 tags is empty
            'allow_comments'=> $this->allow_comments==='on',
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('create-clips', $this->route('clip'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title'       => 'required',
            'description' => 'max:500',
            'slug'        => 'required',
            'tags'        => 'array',
            'episode'     => 'required|integer',
            'allow_comments'=> 'boolean',

        ];
    }
}
