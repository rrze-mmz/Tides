@extends('layouts.app')

@section('content')
    <main class="container  mx-auto  mt-32 md:mt-32 h-screen">
        <div class="flex flex-col content-center justify-center place-content-center items-center">
            <h2 class="font-bold text-2xl">{{ $clip->title }}</h2>

            <p class="pt-3">
                {{ $clip->description }}
            </p>
        </div>
        <div class="flex content-center justify-center">
            <video src="/videos/GUs2ovCy9TosQy3b5y0rxpaS2TvAtFcRAu4pK12k.mp4"
                   class="mejs__player w-full" width="100%" height="70%"
                   data-mejsoptions='{"alwaysShowControls": "true"}'>

            </video>
        </div>
        </main>
@endsection
