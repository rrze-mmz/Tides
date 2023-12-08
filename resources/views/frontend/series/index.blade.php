@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-32 h-auto md:mt-32">
        <div class="pb-10">
            @include('frontend.search._searchbar')
        </div>

        <div class="flex flex-col place-content-center content-center items-center justify-center">
            <h2 class="text-2xl font-bold dark:text-white">{{ __('series.frontend.index.Series index') }}</h2>
        </div>
        <ul class="flex-row">
            <div class="grid grid-cols-4 gap-4">
                @forelse($series as $singleSeries)
                    <li class="my-2 w-full rounded bg-white dark:bg-gray-900 p-4">
                        @include('backend.series._card',['series'=> $singleSeries])
                    </li>
                @empty
                    <li class="my-2 w-full rounded bg-white p-4">
                        {{ __('series.frontend.index.no series') }}
                    </li>
                @endforelse
            </div>

            <div class="py-10">
                {{ $series->links() }}
            </div>
        </ul>
    </main>
@endsection
