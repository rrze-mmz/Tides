@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black text-2xl flex-col dark:text-white dark:border-white font-normal pb-2">
        <div class="font-semibold ">
            {{ __('chapter.backend.chapters for series', [
                                        'seriesTitle' => $series->title,
                                        'seriesID' => $series->id
                                        ]) }}
        </div>
    </div>
    @include('backend.seriesChapters.index._list-chapters')
    @include('backend.seriesChapters.index._new-chapter')
@endsection
