@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black text-2xl flex-col dark:text-white dark:border-white font-normal pb-2">
        <div class="font-semibold ">
            Reorder clips for: <span class="pl-2 font-semibold">{{ $series->title }}</span>
        </div>
    </div>
    <x-list-clips :series="$series" :clips="$clips" dashboardAction="@can('edit-series', $series)"
                  :reorder="true" />
@endsection
