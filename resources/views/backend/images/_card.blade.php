<div class="w-full py-4 px-4 mx-4 h-full bg-white rounded border" id="image">
    <h2 class="text-xl font-normal py-4 -ml-5 mb-3 border-l-4 border-blue-600 pl-4 ">
        {{ str($type)->ucfirst() }} Image
    </h2>

    <div class="">
        <img src="{{  asset('images/'.$model->image->file_name) }}" alt="{{ $model->image->description }}">
    </div>

    <div class="flex pt-4 w-full">
        @if ($model->image->id != (int)config('settings.portal.default_image_id'))
            <div>
                <x-button class="bg-blue-600 hover:bg-blue-700">
                    Set Default image
                </x-button>
            </div>
        @endif
    </div>

    <div class="flex pt-3 ">
        <form
            method="POST"
            class="px-2 w-full pt-4"
            action="{{ route('update.'.$type.'.image', $model) }}"
        >
            @method('PUT')
            @csrf

            <div class="flex flex-col">
                <div class="w-full">
                    <select
                        class="w-full select2-tides-images focus:outline-none focus:bg-white focus:border-blue-500"
                        name="imageID"
                        style="width: 100%"
                    >
                    </select>
                    @error('imageID')
                    <div class="text-red-600 mt-2">{{ $message }}</div>
                    @enderror
                </div>
                @if($type === 'series')
                    <div class="flex items-center pt-2">
                        <div class="pr-2">
                            <label for="assignClips">
                                Overwrite image in clips
                            </label>
                        </div>
                        <x-checkbox name="assignClips" label="Override clips also"></x-checkbox>
                    </div>
                @endif
            </div>

            <x-button class="bg-blue-600 hover:bg-blue-700 mt-3">
                Assign selected image
            </x-button>
        </form>
    </div>
</div>
