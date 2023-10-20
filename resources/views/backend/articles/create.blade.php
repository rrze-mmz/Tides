@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 font-semibold font-2xl">
        Create an article
    </div>

    <div class="flex p-2">
        <form action="{{ route('articles.store')}}"
              method="POST" class="w-4/5"
        >
            @csrf
            <div class="flex flex-col gap-3">
                <x-form.input field-name="title_de"
                              input-type="text"
                              :value="old('title_de')"
                              label="Article title [DE]"
                              :full-col="true"
                              :required="true"
                />
                <x-form.input field-name="slug"
                              input-type="text"
                              :value="old('slug')"
                              label="Slug"
                              :full-col="true"
                              :required="true"
                />
                <x-form.input field-name="title_en"
                              input-type="text"
                              :value="old('title_en')"
                              label="Article title [EN]"
                              :full-col="true"
                              :required="true"
                />
                <x-form.textarea field-name="content_de"
                                 :value="old('content_de')"
                                 label="Content [DE]"
                />

                <x-form.textarea field-name="content_en"
                                 :value="old('content_en')"
                                 label="Content [EN]"
                />

                <x-form.toggle-button :value="old('is_published','true')"
                                      label="Is published"
                                      field-name="is_published"
                />
                <div class="col-span-7 w-4/5 pt-10">
                    <x-button type="submit"
                              class="bg-blue-600 hover:bg-blue-700">
                        Create Article
                    </x-button>
                    <a href="{{ route('articles.index') }}">
                        <x-button
                            class="bg-gray-600 hover:bg-gray-700"
                        >
                            Back to articles list
                        </x-button>
                    </a>
                </div>
            </div>
        </form>
    </div>

@endsection
