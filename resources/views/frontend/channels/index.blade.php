@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-32 h-auto md:mt-32">
        <div class="pb-10">
            @include('frontend.search._searchbar')
        </div>

        <div class="flex flex-col place-content-center content-center items-center justify-center">
            <h2 class="text-2xl font-bold dark:text-white"> Active channels </h2>
        </div>

        @forelse($channels as $channel)

        @empty
            <div class="flex justify-center">
                <div class="dark:text-white pt-10 text-2xl"> No channels available</div>
            </div>

        @endforelse
    </main>
@endsection
