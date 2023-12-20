@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl font-normal dark:text-white dark:border-white">
        Chapter for Series: {{$series->title}} / SeriesID {{ $series->id }}
    </div>
    @include('backend.seriesChapters.index._new-chapter')
    @include('backend.seriesChapters.index._list-chapters')
@endsection
