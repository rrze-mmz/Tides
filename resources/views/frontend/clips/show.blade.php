@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-6 md:mt-36">
        <div class="flex justify-between pb-2 border-b-2 border-black">
            <h2 class="text-2xl font-bold">{{ $clip->title }} [ID: {{ $clip->id }}]</h2>
            @can('edit-clips', $clip)
                <a class="mt-2 py-2 px-8 text-white bg-blue-500 rounded shadow hover:bg-blue-600
                        focus:shadow-outline focus:outline-none"
                   href="{{ $clip->adminPath() }}"
                > Back to edit page </a>
            @endcan
        </div>

        @if (!is_null($clip->assets()->first()))
            @include('frontend.clips._player',['asset'=> $clip->assets()])
        @endif

        <div class="flex justify-between py-2">
            @if(!is_null($previousNextClipCollection->get('previousClip')))
                <x-form.button :link="$previousNextClipCollection->get('previousClip')->path()"
                               type="submit"
                               text="Previous"
                />
            @endif

            @if(!is_null($previousNextClipCollection->get('nextClip')))
                <x-form.button :link="$previousNextClipCollection->get('nextClip')->path()"
                               type="submit"
                               text="Next"
                />
            @endif
        </div>

        @if($clip->description !== null)
            <div class="flex flex-col pt-10">
                <h2 class="text-2xl font-semibold pb-2 w-full border-b-2 border-black">Description</h2>
                <p class="pt-4">
                    {{ $clip->description }}
                </p>
            </div>
        @endif

        @if ($clip->tags->isNotEmpty())
            <div class="flex flex-col pt-10 ">
                <h2 class="text-2xl font-semibold pb-2 w-full border-b-2 border-black">Tags</h2>
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
                <h2 class="text-2xl font-semibold pb-2 border-b-2 border-black">Comments</h2>

                <livewire:comments-section :clip="$clip"/>
                @livewireScripts

            </div>
        @endauth

    </main>
@endsection
