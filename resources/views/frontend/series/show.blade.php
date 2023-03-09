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

        <div class="flex">

            <div x-data="{ open: false }">
                <div class="flex pt-4 pr-4 w-full">
                    <a href="#courseFeeds"
                       x-on:click="open = ! open"
                       class="flex px-4 py-2 bg-blue-800 border border-transparent rounded-md
                    font-semibold text-xs text-white uppercase tracking-widest
                    hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900
                    focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Feeds
                        <x-heroicon-o-rss class="ml-4 w-4 h-4 fill-white"/>
                    </a>
                </div>
                <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-0"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-10"
                     x-transition:leave-end="opacity-0 translate-y-0" class="w-full p-4 ">
                    <ul>
                        @foreach($assetsResolutions as $key=>$resolutionText)
                            <li>
                                <a href="{{route('frontend.series.feed', [$series, $resolutionText])}}"
                                   class="underline">
                                    {{ $resolutionText }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @auth()
                <div>
                    <livewire:subscribe-section :series="$series"/>
                </div>
            @endauth
        </div>

        <div class="flex justify-around pt-8 pb-3 border-b-2 border-gray-500">

            <div class="flex items-center w-1/4">
                <x-heroicon-o-clock class="w-6 h-6"/>
                <span class="pl-3">
                    {{
                    $series->clips
                    ->sortByDesc('semester_id')
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

