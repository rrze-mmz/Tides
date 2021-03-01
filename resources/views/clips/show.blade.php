@extends('layouts.app')

@section('content')
    <main class="container  mx-auto  mt-32 md:mt-32 h-screen">
        <div class="flex flex-col content-center justify-center place-content-center items-center">
            <h2 class="font-bold text-2xl">{{ $clip->title }}</h2>

            <p class="pt-3">
                {{ $clip->description }}
            </p>
        </div>

        </main>
@endsection
