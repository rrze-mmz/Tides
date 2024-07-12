@php use App\Models\Setting;use Illuminate\Support\Str; @endphp
@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-6 md:mt-12">
        <div class="pb-10">
            @include('frontend.search._searchbar')
        </div>
        <div class="flex justify-between">
            <div>
                @include('layouts.breadcrumbs')
            </div>
            @can('edit-podcast', $podcast)
                <div>
                    <a href="{{ route('podcasts.edit', $podcast) }}"
                    >
                        <x-button class="bg-green-500 hover:bg-green-700"
                        >
                            Edit podcast
                        </x-button>
                    </a>
                </div>
            @endcan
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Podcast Information -->
            <div class="p-6 rounded-lg  h-200 dark:text-white">
                <div class="mb-4">
                    <img
                        @if(!is_null($podcast->image_id))
                            src="{{ asset('images/'.$podcast->cover->file_name) }}"
                        @else
                            src="/podcast-files/covers/PodcastDefaultFAU.png"
                        @endif
                        alt="{{ $podcast->title }} cover image"
                        class="w-full h-auto rounded-md">
                </div>
                <h1 class="text-2xl font-bold mb-2">
                    {{$podcast->title}} @can('administrate-superadmin-portal-pages')
                        /  ID: {{$podcast->id}}
                    @endcan
                </h1>
                <div class="w-full prose prose-lg dark:prose-invert">
                    <p class=" mb-2 text-black">
                        {!! $podcast->description !!}
                    </p>
                </div>

                <div class="dark:text-white">
                    @if($podcast->getPrimaryPresenters()->count() > 0 )
                        <span class="text-gray-600 dark:text-white">
                        Hosted by:
                    </span>
                        <span class="text-gray-800 font-semibold dark:text-white">
                     {{ $podcast->getPrimaryPresenters()->map(fn($presenter) => $presenter->full_name)->join(', ') }}
                    </span>
                    @endif
                </div>
                <div class="dark:text-white">
                    @if($podcast->getPrimaryPresenters(primary: false)->count() > 0 )
                        <span class="text-gray-600 dark:text-white">
                        Guests:
                    </span>
                        <span class="text-gray-800 font-semibold dark:text-white">
                    {{
                                $podcast->getPrimaryPresenters(primary: false)
                                ->map(fn($presenter) => $presenter->full_name)
                                ->join(', ')
                     }}
                    </span>
                    @endif
                </div>
                <div class="mt-4">
                    <span class="text-gray-600 dark:text-white">Categories:</span>
                    <span
                        class="inline-block bg-blue-200 text-blue-800 text-sm px-2 py-1 rounded-full">Podcast</span>
                    <span
                        class="inline-block bg-green-200 text-green-800 text-sm px-2 py-1 rounded-full">Test</span>
                </div>
            </div>

            <!-- Podcast Episodes -->
            <div class="p-6 rounded-lg ">
                <div class=" space-y-4 ">
                    <!-- Episode 1 -->
                    @foreach($podcast->episodes()->orderBy('episode_number')->get() as $episode)
                        <div
                            class="p-4 border rounded-lg bg-white shadow-md
                                    dark:bg-slate-900"
                        >
                            <h3 class="text-lg font-semibold mb-2 dark:text-white">
                                {{ $episode->episode_number.' - '.$episode->title }}
                            </h3>
                            <div class="">
                                <p class=" mb-2 text-black dark:text-white">
                                    @if($episode->description==='')
                                        {!!  Str::limit(' Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab, assumenda atque beatae deserunt dolorem ducimus enim error illo incidunt odio pariatur, possimus quaerat quasi quidem quos temporibus unde vero. Quo?', 120, ' (...)') !!}
                                    @else
                                        {{ Str::limit(removeHtmlElements($episode->description), 250, ' (...)') }}
                                    @endif
                                </p>
                            </div>
                            <div class="pt-10">
                                <a href="{{ route('frontend.podcasts.episode.show', [$podcast, $episode]) }}"
                                   class="text-blue-500 hover:underline pt-8">Episode details</a>
                            </div>

                        </div>
                    @endforeach
                    <!-- Add more episodes as needed -->
                </div>
            </div>
        </div>
    </main>
@endsection
