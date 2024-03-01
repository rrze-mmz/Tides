@extends('layouts.backend')

@section('content')
    <div class="mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
            dark:border-white dark:text-white">
        <div class="flex text-2xl">
            Activities Index
        </div>
    </div>
    <livewire:activities-data-table />
@endsection
