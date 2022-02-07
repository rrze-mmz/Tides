@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-32 h-auto md:mt-32">
        <div class="flex flex-col justify-center content-center items-center place-content-center">
            <h2 class="text-2xl font-bold">Series index</h2>
        </div>
        <ul>
            @forelse($series as $singleSeries)
                <li class="w-full p-4 bg-white my-2 rounded ">
                    @include('backend.series._card',['series'=> $singleSeries])
                </li>
            @empty
                <li class="w-full p-4 bg-white my-2 rounded">
                    Portal has no series yet!
                </li>

                <div class="pt-10 py-10">
                    <x-form.button :link="route('series.create')" type="submit" text="Create new series"/>
                </div>
            @endforelse
            <div class="py-10">
                {{ $series->links() }}
            </div>
        </ul>
    </main>
@endsection
