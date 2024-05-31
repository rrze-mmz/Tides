@extends('layouts.backend')

@section('content')
    <div class="mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
                dark:text-white dark:border-white"
    >
        Create a livestream
    </div>
    <div class="flex p-2">
        <form action="{{ route('livestreams.store') }}"
              method="POST" class="w-4/5">
            @csrf

            <div class="flex flex-col gap-3">
                <x-form.input field-name="name"
                              input-type="text"
                              :value="old('name')"
                              label="Livestream name"
                              :full-col="true"
                              :required="true"
                />

                <x-form.input field-name="opencast_location_name"
                              input-type="text"
                              :value="old('opencast_location_name')"
                              label="Opencast location name"
                              :full-col="true"
                              :required="true"
                />

                <x-form.input field-name="app_name"
                              input-type="text"
                              :value="old('app_name')"
                              label="Wowza App name"
                              :full-col="true"
                              :required="true"
                />

                <x-form.input field-name="content_path"
                              input-type="text"
                              :value="old('content_path')"
                              label="Wowza content path"
                              :full-col="true"
                              :required="true"
                />

                <x-form.toggle-button :value="true"
                                      label="Transcoder"
                                      field-name="has_transcoder"
                />

                <div class="col-span-7 w-4/5 pt-10">
                    <x-form.button :link="$link=false"
                                   type="submit" text="Create livestream" />
                </div>
            </div>

        </form>
@endsection
