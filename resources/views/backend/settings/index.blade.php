@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Settings index
    </div>
    <div class="grid grid-cols-4 gap-4 pt-4">
        <x-settings-card route="{{route('settings.portal.show')}}">
            <x-slot name="title">
                Main Portal settings
            </x-slot>
            <x-slot name="text">
                Settings
            </x-slot>
        </x-settings-card>
        <x-settings-card route="{{route('settings.opencast.show')}}">
            <x-slot name="title">
                Opencast
            </x-slot>
            <x-slot name="text">
                Settings
            </x-slot>
        </x-settings-card>
        <x-settings-card route="{{route('settings.streaming.show')}}">
            <x-slot name="title">
                Streaming
            </x-slot>
            <x-slot name="text">
                Settings
            </x-slot>
        </x-settings-card>
        <x-settings-card route="{{route('settings.elasticSearch.show')}}">
            <x-slot name="title">
                Elasticsearch
            </x-slot>
            <x-slot name="text">
                Settings
            </x-slot>
        </x-settings-card>
    </div>
@endsection
