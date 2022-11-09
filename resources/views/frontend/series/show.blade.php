@php use App\Enums\Acl; @endphp
@extends('layouts.frontend')

@section('content')
    <div class="container mx-auto mt-32 md:mt-32">
        <div class="flex justify-between pb-2 border-b-2 border-black">
            <h2 class="text-2xl font-bold">{{ $series->title }} [ID: {{ $series->id }}]</h2>
            @cannot('view-assistant-menu-items')
                @if( str()->contains($series->fetchClipsAcls(),[Acl::PASSWORD->lower()]))
                    <livewire:unlock-object :model="$series"/>
                    @livewireScripts
                @endif
            @endcannot
            @can('edit-series', $series)
                <x-form.button :link="$series->adminPath()" type="submit" text="Edit series"/>
            @endcan

        </div>

        @if($series->description!=='')
            <div class="flex flex-col pt-4">
                <h2 class="text-2xl font-semibold">{{ __('common.description') }}</h2>
                <p class="pt-4 leading-loose">
                    {!! $series->description !!}
                </p>
            </div>
        @endif

        @auth()
            <livewire:subscribe-section :series="$series"/>
        @endauth

        <div class="flex justify-around pt-8 pb-3 border-b-2 border-gray-500">

            <div class="flex items-center w-1/4">
                <x-heroicon-o-clock class="w-6 h-6"/>
                <span class="pl-3">
                    {{
                    $series->clips
                    ->sortBy('semester_id')
                    ->map(function ($clip){ return $clip->semester;})
                    ->pluck('name')
                    ->unique()
                    ->implode(', ')
                    }}
                </span>
            </div>

            <div class="flex items-center w-1/4">
                <x-heroicon-o-calendar class="w-6 h-6"/>
                <span class="pl-3"></span>
            </div>

            <div class="flex items-center w-1/4">
                <x-heroicon-o-upload class="w-6 h-6"/>
                <span class="pl-3"> {{ $series->latestClip?->updated_at }} </span>
            </div>

            <div class="flex items-center w-1/4">
                <x-heroicon-o-eye class='w-6 h-6'/>
                <span class="pl-3"> {{ __('series.frontend.views', ['counter' => 10]) }} </span>
            </div>

        </div>
        @auth()
            <div class="flex flex-col pt-10">
                <h2 class="text-2xl font-semibold pb-2 border-b-2 border-black">
                    {{ __('clip.frontend.comments') }}
                </h2>
                <livewire:comments-section :model="$series" :type="'frontend'"/>
                @livewireScripts

            </div>
        @endauth
        @include('backend.clips.list')

    </div>
@endsection

