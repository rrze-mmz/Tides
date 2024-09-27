@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl ">
        <p>
            <span class="italic">{{ $series->title }} / SeriesID {{ $series->id }}</span> chapter edit
        </p>
    </div>

    <div class="ml-5">
        <div class="flex pt-5">
            <form action=""
                  class="pt-6 w-4/5">
                <div class="flex flex-col gap-3">
                    <x-form.input field-name="position"
                                  input-type="number"
                                  :value="$chapter->position"
                                  label="{{ __('series.common.episode') }}"
                                  :full-col="false"
                                  :required="true" />
                    <x-form.input field-name="title"
                                  input-type="text"
                                  :value="$chapter->title"
                                  label="{{ __('common.metadata.title') }}"
                                  :full-col="true"
                                  :required="true" />
                    <x-form.toggle-button :value="$chapter->default"
                                          label="{{ __('chapter.backend.default chapter') }}"
                                          field-name="default"
                    />
                    <div class="pt-6">
                        <x-button class="bg-blue-600 hover:bg-blue-700"
                                  type="submit"
                        >
                            {{ __('chapter.backend.actions.delete chapter') }}
                        </x-button>
                        <a href="{{route('series.chapters.index',$series)}}">
                            <x-button class="bg-green-600 hover:bg-green-700"
                                      type="button"
                            >
                                {{ __('chapter.backend.actions.back to series chapters') }}
                            </x-button>
                        </a>
                    </div>

                </div>
            </form>
        </div>
        <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
            {{ __('series.common.actions') }}
        </div>
        <div class="flex items-center pt-3 space-x-6">
            <form action="{{route('series.chapters.delete',[$series, $chapter])}}"
                  method="POST">
                @method('DELETE')
                @csrf
                <x-button class="bg-red-600 hover:bg-red-700"
                          type="submit"
                >
                    {{ __('chapter.backend.actions.delete chapter') }}
                </x-button>
            </form>
        </div>
        @include('backend.seriesChapters.edit._add-clips-to-chapter')
        @include('backend.seriesChapters.edit._list-clips-for-chapter')

    </div>

@endsection
