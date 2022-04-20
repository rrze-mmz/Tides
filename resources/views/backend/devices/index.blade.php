@extends('layouts.backend')

@section('content')
    <div class="flex justify-between  pb-2 mb-5 font-semibold border-b border-black font-2xl items-center">
        <div class="flex">
            Devices Index
        </div>
        <div class="flex">
            <x-form.button :link="route('devices.create')" type="submit" text="Create a new device"/>
        </div>
    </div>
    <livewire:devices-data-table/>
@endsection
