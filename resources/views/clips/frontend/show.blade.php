@extends('layouts.frontend')

@section('content')
    <main class="container  mx-auto  mt-32 md:mt-32 h-screen">
        <div class="flex flex-col content-center justify-center place-content-center items-center">
            <h2 class="font-bold text-2xl">{{ $clip->title }} [ID: {{ $clip->id }}]</h2>
        </div>
        <div class="flex content-center justify-center pt-6">
            <video src="{{'/'.$clip->assets->first()->uploadedFile }}"
                   class="mejs__player" width="900px" height="450"
                   data-mejsoptions='{"alwaysShowControls": "true"}'>

            </video>
        </div>
        @auth()
        <div class="flex">
            <a href="{{ $clip->adminPath() }}"> Back to edit page </a>
        </div>
        @endauth
        <p class="flex px-2 py-8">
            <h2 class="font-semibold text-2xl">Description</h2>
            {{ $clip->description }}
        </p>
        </main>
@endsection
