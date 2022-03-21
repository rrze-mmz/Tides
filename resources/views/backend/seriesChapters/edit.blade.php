@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl ">
        <p>
            Course <span class="italic">{{ $series->title }}</span> edit chapter
        </p>
    </div>

    @include('backend.seriesChapters.edit._add-clips-to-chapter')
    @include('backend.seriesChapters.edit._list-clips-for-chapter')

@endsection
