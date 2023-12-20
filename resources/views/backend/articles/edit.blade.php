@extends('layouts.backend')

@section('content')
    <div class="mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
                dark:text-white dark:border-white"
    >
        Edit article: {{ $article->title_de }} | {{ $article->title_de }}
    </div>
    <div class="flex px-2 py-2">
        <form action="{{ route('articles.update',$article) }}"
              method="POST" class="w-4/5">
            @csrf
            @method('PUT')

            <div class="flex flex-col gap-3">
                <x-form.input field-name="title_de"
                              input-type="text"
                              :value="old('title_de', $article->title_de)"
                              label="Article title [DE]"
                              :full-col="true"
                              :required="true"
                />
                <div class="flex flex-col gap-3">
                    <x-form.input field-name="slug"
                                  input-type="text"
                                  :value="old('slug', $article->slug)"
                                  label="Slug"
                                  :full-col="true"
                                  :required="true"
                    />

                    <x-form.input field-name="title_en"
                                  input-type="text"
                                  :value="old('title_en', $article->title_en)"
                                  label="Article title [EN]"
                                  :full-col="true"
                                  :required="true"
                    />

                    <x-form.textarea field-name="content_de"
                                     :value="old('content_de', $article->content_de)"
                                     label="Content [DE]"
                    />

                    <x-form.textarea field-name="content_en"
                                     :value="old('content_en', $article->content_en)"
                                     label="Content [EN]"
                    />

                    <x-form.toggle-button :value="$article->is_published"
                                          label="Is published"
                                          field-name="is_published"
                    />

                    <div class="col-span-7 w-4/5 pt-10">
                        <x-button class="bg-blue-600 hover:bg-blue-700">
                            Update Article
                        </x-button>
                        <a href="{{ route('articles.index') }}">
                            <x-button type="button" class="bg-gray-600 hover:bg-gray-700">
                                Back to articles list
                            </x-button>
                        </a>
                    </div>
                </div>

        </form>
@endsection
