@extends('layouts.backend')

@section('content')
    <div class="mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
                dark:text-white dark:border-white"
    >
        <div class="flex text-2xl">
            Livestreams Index
        </div>
        <div class="flex">
            <a href="{{route('livestreams.create')}}">
                <x-button class="flex items-center bg-blue-700 hover:bg-blue-700">
                    <div class="pr-2">
                        Create a new livestream room
                    </div>
                    <div>
                        <x-heroicon-o-plus-circle class="h-6 w-6" />
                    </div>
                </x-button>
            </a>
        </div>
    </div>
    <livewire:livestreams-data-table />
@endsection