@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl dark:text-white dark:border-white">
        Series index
    </div>
    <livewire:series-data-table />
@endsection

