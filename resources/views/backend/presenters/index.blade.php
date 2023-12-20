@extends('layouts.backend')

@section('content')
    <div class="mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
    dark:text-white dark:border-white">
        <div class="flex">
            Presenters Index
        </div>
        <div class="flex">
            <x-form.button :link="route('presenters.create')" type="submit" text="Create a new presenter" />
        </div>
    </div>
    <livewire:presenter-data-table />
@endsection
