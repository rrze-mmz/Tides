@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-32 md:mt-32">
        <div class="flex justify-between pb-2 border-b-2 border-black">
            <h2 class="text-2xl font-bold">{{ $clip->title }} [ID: {{ $clip->id }}]</h2>
            @can('edit-clips', $clip)
                <a  class="mt-2 py-2 px-8 text-white bg-blue-500 rounded shadow hover:bg-blue-600 focus:shadow-outline focus:outline-none"
                    href="{{ $clip->adminPath() }}"
                > Back to edit page </a>
            @endcan
        </div>

        @if (!is_null($clip->assets()->first()))

                @include('frontend.clips._player',['asset'=> $clip->assets()])

        @endif

            <div class="flex flex-col pt-20">
                <h2 class="text-2xl font-semibold">Description</h2>
                <p class="pt-4">
                    {{ $clip->description }}
                </p>
            </div>

        @auth
            <div class="flex flex-col pt-20">
                <h2 class="text-2xl font-semibold">Comments</h2>
                @include('frontend.clips._comments')
            </div>
        @endauth

        </main>
@endsection
