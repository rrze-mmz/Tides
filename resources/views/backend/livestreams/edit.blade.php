@extends('layouts.backend')

@section('content')
    <div class="mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
                dark:text-white dark:border-white"
    >
        Edit Livestream: {{ $livestream->name }}
    </div>
    <div class="flex px-2 py-2">
        <form action="{{ route('livestreams.update',$livestream) }}"
              method="POST" class="w-4/5">
            @csrf
            @method('PUT')

            <div class="flex flex-col gap-3">
                <x-form.input field-name="name"
                              input-type="text"
                              :value="old('name', $livestream->name)"
                              label="Livestream name"
                              :full-col="true"
                              :required="true"
                />

                <x-form.input field-name="opencast_location_name"
                              input-type="text"
                              :value="old('name', $livestream->opencast_location_name)"
                              label="Opencast location name"
                              :full-col="true"
                              :required="true"
                />

                <x-form.input field-name="app_name"
                              input-type="text"
                              :value="old('app_name', $livestream->app_name)"
                              label="Wowza App name"
                              :full-col="true"
                              :required="true"
                />

                <x-form.input field-name="content_path"
                              input-type="text"
                              :value="old('content_path', $livestream->content_path)"
                              label="Wowza content path"
                              :full-col="true"
                              :required="true"
                />

                <x-form.toggle-button :value="$livestream->has_transcoder"
                                      label="Transcoder"
                                      field-name="has_transcoder"
                />

                <div class="col-span-7 mt-10 w-4/5 space-x-4">
                    <x-button class="bg-blue-600 hover:bg-blue-700">
                        Update livestream infos
                    </x-button>
                    <a href="{{route('livestreams.index')}}">
                        <x-button type="button" class="bg-gray-600 hover:bg-gray:700">
                            Back to livestreams list
                        </x-button>
                    </a>
                </div>
            </div>

        </form>
@endsection
