@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-12 h-auto md:mt-12">
        <div class="pb-10">
            @include('frontend.search._searchbar')
        </div>

        <div class="flex flex-col place-content-center content-center items-center justify-center">
            <h2 class="text-2xl font-bold dark:text-white">{{ __('clip.frontend.index.Clips index') }}</h2>
        </div>

        <ul class="flex-row">
            <div class="grid grid-cols-4 gap-4">
                @forelse ($clips as $clip)
                    <li class="my-2 w-full rounded bg-white dark:bg-gray-900 p-4">
                        @include('backend.clips._card',['clip'=> $clip])
                    </li>
                @empty
                    <li class="my-2 w-full rounded bg-white p-4">
                        {{ __('clip.frontend.index.Portal has no clips yet!') }}
                    </li>
                @endforelse
            </div>
            <div class="py-10">
                {{ $clips->links() }}
            </div>
        </ul>
    </main>
@endsection
