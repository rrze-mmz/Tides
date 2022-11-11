@extends('layouts.backend')

@section('content')
    <div class="flex justify-between  pb-2 mb-5 font-semibold border-b border-black font-2xl items-center">
        <div class="flex">
            Devices Index
        </div>
        <div class="flex">
            <a href="{{route('devices.create')}}">
                <x-button class="bg-blue-700 hover:bg-blue-700">
                    Create a new device
                </x-button>
            </a>
        </div>
    </div>
    <livewire:devices-data-table/>
@endsection
