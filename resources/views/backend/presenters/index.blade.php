@extends('layouts.backend')

@section('content')
    <div class="flex justify-between  pb-2 mb-5 font-semibold border-b border-black font-2xl items-center">
        <div class="flex">
            Presenters Index
        </div>
        <div class="flex">
            <x-form.button :link="route('presenters.create')" type="submit" text="Create a new presenter"/>
        </div>
    </div>
    <livewire:presenter-data-table/>
@endsection
