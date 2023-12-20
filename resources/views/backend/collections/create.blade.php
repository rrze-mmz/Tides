@extends('layouts.backend')

@section('content')
    <div class="mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
                dark:text-white dark:border-white"
    >
        Create new collection
    </div>
    <div class="flex px-2 py-4">
        <form action="{{route('collections.store')}}"
              method="POST"
              class="w-4/5"
        >
            @csrf

            <div class="flex flex-col gap-3">

                <x-form.input field-name="position"
                              input-type="number"
                              value="1"
                              label="Position"
                              :full-col="false"
                              :required="false"
                />

                <x-form.input field-name="title"
                              input-type="text"
                              :value="old('title')"
                              label="Title"
                              :full-col="true"
                              :required="true"
                />

                <x-form.textarea field-name="description"
                                 :value="old('description')"
                                 label="Description"
                />

                <x-form.toggle-button :value="true"
                                      label="Public"
                                      field-name="is_public"
                />

            </div>
            <div class="pt-10">
                <x-form.button :link="$link=false" type="submit" text="Create collection" />
            </div>
        </form>
    </div>
@endsection
