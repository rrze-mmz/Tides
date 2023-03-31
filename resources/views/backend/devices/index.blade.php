@extends('layouts.backend')

@section('content')
    <div class="flex justify-between  pb-2 mb-5 font-semibold border-b border-black font-2xl items-center">
        <div class="flex">
            Devices Index
        </div>
        <div class="flex">
            <a href="{{route('devices.create')}}">
                <x-button class="bg-blue-700 hover:bg-blue-700 flex items-center">
                    <div class="pr-2">
                        Create a new device
                    </div>
                    <div>
                        <x-heroicon-o-plus-circle class="w-6 h-6"/>
                    </div>
                </x-button>
            </a>
        </div>
    </div>
    <livewire:devices-data-table/>
@endsection
