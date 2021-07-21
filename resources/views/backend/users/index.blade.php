@extends('layouts.backend')

@section('content')
    <div class="flex justify-between  pb-2 mb-5 font-semibold border-b border-black font-2xl items-center">
       <div class="flex">
           Users Index
       </div>
        <div class="flex">
            <x-form.button :link="route('users.create')" type="submit" text="Create a new User"/>
        </div>
    </div>
    <livewire:user-data-table/>
    @livewireScripts
@endsection
