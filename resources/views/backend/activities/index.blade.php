@extends('layouts.backend')

@section('content')
    <div class="flex justify-between  pb-2 mb-5 font-semibold border-b border-black font-2xl items-center">
        <div class="flex">
            Activities Index
        </div>
    </div>
    <livewire:activities-data-table/>
@endsection
