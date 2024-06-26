@php use App\Models\Setting; @endphp
@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-6 md:mt-12">
        <div class="pb-10">
            @include('frontend.search._searchbar')
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Podcast Information -->
            <div class="p-6 rounded-lg  h-200 dark:text-white">
                <div class="mb-4">
                    <img src="{{ '/podcasts-files/'.$podcast->cover_image_url}}" alt="Podcast Cover"
                         class="w-full h-auto rounded-md">
                </div>
                <h1 class="text-2xl font-bold mb-2">{{$podcast->title}}</h1>
                <p class="text-gray-700 dark:text-white mb-4">
                    {{ $podcast->description }}
                </p>
                <div>
                    <span class="text-gray-600 dark:text-white">Hosted by:</span> <span
                        class="text-gray-800 font-semibold">{{ $podcast->presenters->first() }}</span>
                </div>
                <div class="mt-4">
                    <span class="text-gray-600 dark:text-white">Categories:</span>
                    <span
                        class="inline-block bg-blue-200 text-blue-800 text-sm px-2 py-1 rounded-full">Category 1</span>
                    <span
                        class="inline-block bg-green-200 text-green-800 text-sm px-2 py-1 rounded-full">Category 2</span>
                </div>
            </div>

            <!-- Podcast Episodes -->
            <div class="p-6 rounded-lg ">
                <div class=" space-y-4">
                    <!-- Episode 1 -->
                    @foreach($podcast->episodes()->orderBy('episode_number')->get() as $episode)
                        <div class="p-4 border rounded-lg bg-white shadow-md">
                            <h3 class="text-lg font-semibold mb-2">
                                {{ $episode->episode_number.' - '.$episode->title }}
                            </h3>
                            <p class="text-gray-700 mb-2">{{$episode->description}}</p>
                            <a href="#" class="text-blue-500 hover:underline">Listen episode</a>
                            {{--                            <div class="mt-4">--}}
                            {{--                                <audio controls class="w-full">--}}
                            {{--                                    <source src="path/to/episode1.mp3" type="audio/mp3">--}}
                            {{--                                    Your browser does not support the audio element.--}}
                            {{--                                </audio>--}}
                            {{--                            </div>--}}
                        </div>
                    @endforeach
                    <!-- Add more episodes as needed -->
                </div>
            </div>
        </div>
    </main>
@endsection
