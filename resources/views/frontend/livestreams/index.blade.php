@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-6 md:mt-12">
        <div class="flex items-center border-b-2 border-black dark:border-white pb-2">
            <div class="flex-grow">
                <h2 class="text-2xl font-bold dark:text-white">Active livestreams</h2>
            </div>
        </div>
        <ul class="flex-row">
            <div class="grid grid-cols-4 gap-4">
                @forelse ($livestreams as $livestream)
                    <li class="my-2 w-full rounded bg-white dark:bg-gray-900 p-4">
                        @include('backend.clips._card',['clip'=> $livestream->clip])
                    </li>
                @empty
                    <div class="pt-10 dark:text-white">
                        No active livestreams found atm
                    </div>
                @endforelse
            </div>
        </ul>
    </main>
@endsection
