@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-32 h-screen md:mt-32">
        <div class="flex flex-col justify-center content-center items-center place-content-center">
            <h2 class="text-2xl font-bold">{{ __('clip.frontend.index.Clips index') }}</h2>
        </div>

        <ul class="flex-row">
            <div class="grid grid-cols-4 gap-4">
                @forelse ($clips as $clip)
                    <li class="w-full p-4 bg-white my-2 rounded ">
                        @include('backend.clips._card',['clip'=> $clip])
                    </li>
                @empty
                    <li class="w-full p-4 bg-white my-2 rounded">
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

