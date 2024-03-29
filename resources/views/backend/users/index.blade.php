@extends('layouts.backend')

@section('content')
    <div class="mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
                dark:text-white dark:border-white"
    >
        <div class="flex text-2xl">
            Users Index
        </div>
        <div class="flex">
            <x-form.button :link="route('users.create')" type="submit" text="Create a new User" />
        </div>
    </div>
    <livewire:user-data-table />
@endsection
