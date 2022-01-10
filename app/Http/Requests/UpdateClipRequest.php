<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UpdateClipRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'slug'           => Str::slug($this->title),
            'tags'           => $this->tags = $this->tags ?? [], //set empty array if select2 tags is empty
            'acls'           => $this->acls = $this->acls ?? [], //set empty array if select2 acls is empty
            'presenters'     =>
                $this->presenters = $this->presenters ?? [], //set empty array if select2 presenters is empty
            'allow_comments' => $this->allow_comments === 'on',
            'isPublic'       => $this->isPublic === 'on',
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
            'title'           => ['required'],
            'description'     => ['max:255'],
            'organization_id' => ['required', 'integer'],
            'slug'            => ['required'],
            'semester_id'     => ['required', 'integer'],
            'presenters'      => ['array'],
            'tags'            => ['array'],
            'acls'            => ['array'],
            'episode'         => ['required', 'integer'],
            'allow_comments'  => ['boolean'],
            'password'        => ['nullable', Password::min(8)->mixedCase()],
            'isPublic'        => ['boolean'],
        ];
    }
}
