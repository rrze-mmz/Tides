@extends('layouts.backend')

@section('content')
    <div class="flex justify-between pb-2 mb-5 font-semibold border-b border-black font-2xl items-center">
        <div class="flex">
            Create new image
        </div>
        <div class="flex">
            <a href="{{ route('images.index') }}">
                <x-button class="bg-blue-700 hover:bg-blue-700 flex items-center">
                    <div class="pr-2">
                        <x-heroicon-o-arrow-circle-left class="w-6 h-6"/>
                    </div>
                    <div>
                        Back to images list
                    </div>
                </x-button>
            </a>
        </div>
    </div>
    <div class="flex">
        <form action="{{ route('images.store') }}"
              method="POST"
              class="w-full"
        >
            @csrf
            <x-form.input field-name="description"
                          input-type="description"
                          :value="'image description'"
                          label="{{__('common.forms.description')}}"
                          :full-col="true"
                          :required="true"
            />

            <input type="file"
                   name="image"
                   class="filepond "
                   data-max-file-size="10MB"
            />
            @error('image')
            <div class="col-start-2 col-end-6">
                <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
            </div>
            @enderror
            <x-button class="bg-blue-600 hover:bg-blue-700">
                <div class="flex">
                    <x-heroicon-o-upload class="w-6 h-6"/>
                    <span class="pl-4">
                        Upload file
                    </span>
                </div>
            </x-button>

        </form>
    </div>
@endsection
