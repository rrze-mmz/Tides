@use(App\Enums\Acl)
@extends('layouts.frontend')

@section('content')
    <div class="container mx-auto mt-16 md:mt-16 dark:text-white">
        <div class="pb-10">
            @include('frontend.search._searchbar')
        </div>


        <div class="flex justify-between border-b-2 border-black pb-2 dark:border-white">
            <h2 class="text-2xl font-bold ">{{ $series->title }} [ID: {{ $series->id }}]</h2>
            @cannot('administrate-admin-portal-pages')
                @if( str()->contains($series->fetchClipsAcls(),[Acl::PASSWORD->lower()]))
                    <livewire:unlock-object :model="$series" />
                @endif
            @endcannot
            @can('edit-series', $series)
                <x-form.button :link="$series->adminPath()" type="submit" text="Edit series" />
            @endcan

        </div>
        @if($series->description!=='')
            <div class="prose-lg dark:prose-invert dark:text-white">
                <p class="leading-loose">
                    {!! $series->description !!}
                </p>
            </div>
        @endif

        <div class="flex">

            <div x-data="{ open: false }">
                <div class="flex w-full pt-4 pr-4">
                    <a href="#courseFeeds"
                       x-on:click="open = ! open"
                       class="flex px-4 py-2 bg-blue-800 border border-transparent rounded-md
                    font-semibold text-xs text-white uppercase tracking-widest
                    hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900
                    focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Feeds
                        <x-heroicon-o-rss class="ml-4 h-4 w-4 fill-white" />
                    </a>
                </div>
                <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-0"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-10"
                     x-transition:leave-end="opacity-0 translate-y-0" class="w-full p-4">
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
                    <livewire:subscribe-section :series="$series" />
                </div>
            @endauth
        </div>

        <div class="flex justify-around border-b-2 border-gray-500 pt-8 pb-3">

            <div class="flex w-1/4 items-center">
                <x-heroicon-o-clock class="h-6 w-6" />
                <span class="pl-3">
                    {{  $series->fetchClipsSemester() }}
                </span>
            </div>

            <div class="flex w-1/4 items-center">
                <x-heroicon-o-user class="h-6 w-6" />
                <span class="pl-3">
                    {{
                    $series->presenters->map(function ($presenter) {
                        return $presenter->getFullNameAttribute();
                        })->implode(', ')
                        }}
                </span>
            </div>

            <div class="flex w-1/4 items-center">
                <x-heroicon-o-arrow-up-circle class="h-6 w-6" />
                <span class="pl-3"> {{ $series->latestClip?->updated_at }} </span>
            </div>

            <div class="flex w-1/4 items-center">
                <x-heroicon-o-eye class='h-6 w-6' />
                <span class="pl-3"> {{ __('series.frontend.show.views', ['counter' => $series->views()]) }} </span>
            </div>

        </div>
        @auth()
            <div class="flex flex-col pt-10">
                <h2 class="border-b-2 border-black  dark:border-white pb-2 text-2xl font-semibold">
                    {{ __('clip.frontend.comments') }}
                </h2>
                <livewire:comments-section :model="$series" :type="'frontend'" />
            </div>
        @endauth
        @include('backend.clips.list')

    </div>
@endsection

