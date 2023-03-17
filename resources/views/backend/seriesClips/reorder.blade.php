@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 border-b border-black">
        Reorder clips for: <span class=" font-semibold pl-2 font-2xl">{{ $series->title }}</span>
    </div>

    <div class="flex pt-8 pb-2 text-lg font-semibold border-b border-black mb-4">
        Clips
    </div>
    <x-list-clips :series="$series" :clips="$clips" dashboardAction="@can('edit-series', $series)" :reorder="true"/>
@endsection
