@extends('layouts.backend')

@section('content')
    <div class="mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl">
        <div class="flex">
            Show Search results
        </div>
    </div>
    <livewire:search-data-table />
@endsection
