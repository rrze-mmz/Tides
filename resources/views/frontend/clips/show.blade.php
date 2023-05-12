@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-6 md:mt-12">
        <div class="flex items-center pb-2 border-b-2 border-black">
            <div class="flex-grow">
                <h2 class="text-2xl font-bold">{{ $clip->title }} [ID: {{ $clip->id }}]</h2>
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

        @if (!is_null($clip->assets()->first()))
            @include('frontend.clips._player',['asset'=> $clip->assets()])
        @endif

        <div class="flex justify-between py-2">
            @if(!is_null($previousNextClipCollection->get('previousClip')))
                <a href="{{ $previousNextClipCollection->get('previousClip')->path() }}">
                    <x-button class="bg-blue-600 hover:bg-blue-700">
                        {{ __('common.previous') }}
                    </x-button>
                </a>
            @endif

            @if(!is_null($previousNextClipCollection->get('nextClip')))
                <a href="{{ $previousNextClipCollection->get('nextClip')->path() }}">
                    <x-button class="bg-blue-600 hover:bg-blue-700">
                        {{ __('common.next') }}
                    </x-button>
                </a>
            @endif
        </div>

        @if($clip->description !== null)
            <div class="flex flex-col pt-10">
                <h2 class="text-2xl font-semibold pb-2 w-full border-b-2 border-black">
                    {{ __('common.description') }}
                </h2>
                <p class="pt-4">
                    {!!   $clip->description !!}
                </p>
            </div>
        @endif

        @if ($clip->tags->isNotEmpty())
            <div class="flex flex-col pt-10 ">
                <h2 class="text-2xl font-semibold pb-2 w-full border-b-2 border-black">
                    Tags
                </h2>
                <span class="pt-4 ">
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
                <h2 class="text-2xl font-semibold pb-2 border-b-2 border-black">
                    {{ __('clip.frontend.comments') }}
                </h2>
                <livewire:comments-section :model="$clip" :type="'frontend'"/>
                @livewireScripts

            </div>
        @endauth

    </main>
@endsection
