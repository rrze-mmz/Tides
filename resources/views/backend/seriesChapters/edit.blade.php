@extends('layouts.backend')

@section('content')
    <div class="flex justify-between border-b border-black text-2xl dark:text-white dark:border-white
                font-normal pb-2">
        <div class="font-semibold">
            {!!  __('chapter.backend.edit chapter for series', [
            'chapterPosition' => $chapter->position,
            'chapterTitle' => $chapter->title,
            'seriesTitle' => $series->title
            ])  !!}
        </div>

    </div>

    <div class="ml-5">
        <div>
            <div class="flex mt-6  pb-1  mb-5 font-medium border-b border-black font-3xl dark:text-white">
                {{ __('common.metadata.chapter').' '.__('series.common.actions') }}
            </div>
            <div class="flex space-x-4">
                <div>
                    <a href="{{route('series.chapters.index',$series)}}">
                        <x-button class="bg-green-600 hover:bg-green-700"
                                  type="button"
                        >
                            {{ __('chapter.backend.actions.back to series chapters') }}
                        </x-button>
                    </a>
                </div>
                <div>
                    <x-modals.delete :route="route('series.chapters.delete', [
                                                            'series' => $series,
                                                            'chapter'=> $chapter
                                                             ])"
                                     :btn_text="__('chapter.backend.actions.delete chapter')">
                        <x-slot:title>
                            {{__('chapter.backend.delete.modal title',['chapter_title'=>$chapter->title])}}
                        </x-slot:title>
                        <x-slot:body>
                            {{__('chapter.backend.delete.modal body')}}
                        </x-slot:body>
                    </x-modals.delete>
                </div>
            </div>
        </div>
        @include('backend.seriesChapters.edit._add-clips-to-chapter')
        @include('backend.seriesChapters.edit._list-clips-for-chapter')
    </div>
@endsection
