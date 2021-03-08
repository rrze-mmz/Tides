@extends('layouts.frontend')

@section('content')
    <main class="container  mx-auto  mt-32 md:mt-32 h-screen">
        <div class="flex  justify-between border-b-2  border-black pb-2">
            <h2 class="font-bold text-2xl">{{ $clip->title }} [ID: {{ $clip->id }}]</h2>
            <a  class="focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-green-700 hover:bg-green-600 hover:shadow-lg"
                href="{{ $clip->adminPath() }}"
            > Back to edit page </a>
        </div>

        @if (!is_null($clip->assets()->first()))
            <div class="flex content-center justify-center pt-6">
                <video src="{{'/'.$clip->assets->first()->uploadedFile }}"
                       class="mejs__player" width="900px" height="450"
                       data-mejsoptions='{"alwaysShowControls": "true"}'>

                </video>
            </div>
        @endif

        @auth()
        <div class="flex">

        </div>
        @endauth

        <p class="flex px-2 py-8">
            <h2 class="font-semibold text-2xl">Description</h2>
            {{ $clip->description }}
        </p>
        </main>
@endsection
