@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-6 md:mt-12">
        <div class="flex items-center border-b-2 border-black pb-2 dark:border-white">
            <div class="flex-grow">
                <h2 class="text-2xl font-bold dark:text-white">{{ $clip->title }} [ID: {{ $clip->id }}]</h2>
            </div>
            @can('edit-clips', $clip)
                <div class="flex-none">
                    <a href="{{ route('clips.edit', $clip)}}">
                        <x-button class="bg-blue-500 hover:bg-blue-600">
                            {{ __('clip.frontend.show.Back to clip edit page') }}
                        </x-button>
                    </a>
                </div>
            @endcan
        </div>

        <div class="flex flex-col align-center">
            @if (!is_null($clip->assets()->first()) || $clip->is_livestream)
                @include('frontend.clips._player',['asset'=> $clip->assets()])
            @endif
        </div>

        <div class="flex justify-between py-2">
            @if(!is_null($previousNextClipCollection->get('previousClip')))
                <a class="flex max-w-lg flex-row items-center justify-between"
                   href="{{ $previousNextClipCollection->get('previousClip')->path() }}">
                    <x-button class="bg-blue-600 hover:bg-blue-700 text-sm">
                        <div class="mr-4">
                            <x-heroicon-o-arrow-circle-left class="w-6" />
                        </div>
                        <div>
                            {{ __('common.previous').'-'.$previousNextClipCollection->get('previousClip')->title }}
                        </div>
                    </x-button>
                </a>
            @endif
            @if(!is_null($previousNextClipCollection->get('nextClip')))
                <a class="flex max-w-lg flex-row items-center justify-between"
                   href="{{ $previousNextClipCollection->get('nextClip')->path() }}">
                    <x-button class="bg-blue-600 hover:bg-blue-700 text-sm">
                        <div>
                            {{ __('common.next').'-'.$previousNextClipCollection->get('nextClip')->title }}
                        </div>
                        <div class="ml-4">
                            <x-heroicon-o-arrow-circle-right class="w-6" />
                        </div>
                    </x-button>
                </a>
            @endif
        </div>

        @if($clip->description !== null && $clip->description !=='')
            <div class="pt-10">
                <h2 class="w-full border-b-2 border-black pb-2  dark:border-white text-2xl
                font-semibold dark:text-white"
                >
                    {{ __('common.description') }}
                </h2>
            </div>
            <div class="flex flex-col dark:bg-gray-200">
                <p class="pt-4 bg-gray-50">
                    {!!   $clip->description !!}
                </p>
            </div>
        @endif

        @if ($clip->tags->isNotEmpty())
            <div class="flex flex-col pt-10">
                <h2 class="w-full border-b-2 border-black pb-2 dark:border-white text-2xl
                font-semibold dark:text-white"
                >
                    Tags
                </h2>
                <span class="pt-4">
                        @foreach($clip->tags as $tag)
                        <div
                            class="text-sm mr-1 inline-flex items-center font-bold leading-sm px-3 py-1 bg-green-200
                                    text-green-700 rounded-full"
                        >
                            {{ $tag->name }}
                        </div>
                    @endforeach
                    </span>
            </div>
        @endif
        @can ('view-comments', $clip)
            <div class="flex flex-col pt-10">
                <h2 class="border-b-2 border-black pb-2 text-2xl font-semibold dark:text-white dark:border-white">
                    {{ __('clip.frontend.comments') }}
                </h2>
                <livewire:comments-section :model="$clip" :type="'frontend'" />
                @livewireScripts

            </div>
        @endauth

    </main>
@endsection
