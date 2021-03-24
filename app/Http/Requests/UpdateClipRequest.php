<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class UpdateClipRequest extends FormRequest
{


    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->title),
            'tags' => $this->tags = $this->tags ?? [], //set empty array if select2 tags is empty
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('edit-clips', $this->route('clip'));
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
            'description' => 'min:3|max:255',
            'slug'        => 'required',
            'tags'        => 'array'
        ];
    }
}
