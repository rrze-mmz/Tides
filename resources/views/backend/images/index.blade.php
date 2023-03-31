@extends('layouts.backend')

@section('content')
    <div class="flex justify-between  pb-2 mb-5 font-semibold border-b border-black font-2xl items-center">
        <div class="flex">
            Images Index
        </div>
        @can('administrate-portal-pages')
            <div class="flex">
                <div>
                    <a href="{{ route('images.create') }}">
                        <x-button class="bg-blue-700 hover:bg-blue-700 flex items-center">
                            <div class="pr-2">
                                Create a new image
                            </div>
                            <div>
                                <x-heroicon-o-plus-circle class="w-6 h-6"/>
                            </div>
                        </x-button>
                    </a>
                </div>
            </div>
        @endcan
    </div>
    <livewire:images-data-table/>
@endsection
