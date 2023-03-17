@extends('layouts.backend')

@section('content')
    <div class="flex justify-between  pb-2 mb-5 font-semibold border-b border-black font-2xl items-center">
        <div class="flex">
            Images Index
        </div>
        <div class="flex">
            <div>
                <a href="{{ route('images.create') }}">
                    <x-button class="bg-blue-600 hover:bg-blue-700">
                        create a new image
                    </x-button>
                </a>
            </div>
        </div>
    </div>
    <livewire:images-data-table/>
@endsection
