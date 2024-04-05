@php use Illuminate\Support\Carbon; @endphp
@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black text-2xl flex-col dark:text-white dark:border-white font-normal">
        <div class="font-semibold ">
            Statistics for Series: {{ $obj['info']->title }} / ID: {{ $obj['info']->id }}
        </div>
    </div>
    <div class="flex flex-col py-4 px-2 justify-stretch">
        <div class=" text-xl font-bold text-left rtl:text-right text-gray-500 dark:text-gray-400 pb-10">
            Aufrufe pro Tag pro Clip
        </div>
        <div class="flex w-full pr-10">
            @include('backend.statistics.partial.clips-bar')
        </div>
    </div>
    <div class="flex flex-col py-2 px-2  justify-stretch">
        <div class=" text-xl font-bold text-left rtl:text-right text-gray-500 dark:text-gray-400 pb-10">
            Geolocation Stats
        </div>
        <div class="flex w-full pr-10">
            <div class="w-2/3">
                @include('backend.statistics.partial.geolocation-line')
            </div>
            <div class="flex w-1/3 pr-10">
                @include('backend.statistics.partial.geolocation-pie')
            </div>
        </div>
    </div>

@endsection
