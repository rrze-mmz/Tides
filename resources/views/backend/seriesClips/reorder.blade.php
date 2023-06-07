@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2">
        Reorder clips for: <span class="pl-2 font-semibold font-2xl">{{ $series->title }}</span>
    </div>

    <div class="mb-4 flex border-b border-black pt-8 pb-2 text-lg font-semibold">
        Clips
    </div>
    <x-list-clips :series="$series" :clips="$clips" dashboardAction="@can('edit-series', $series)" :reorder="true"/>
@endsection
