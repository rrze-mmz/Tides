@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Select series for: {{ $clip->title }}
    </div>
    <ul class="flex flex-col">
        @forelse($series as $singleSeries)
            <li class="w-full p-4 bg-white my-2 rounded">
                @include('backend.series._card',['series'=> $singleSeries,'action'=>'assignClip'])
            </li>
        @empty
            <li class="w-full p-4 bg-white my-2 rounded">
                You have no series yet...Maybe create one and the assign it?
            </li>

            <div class="pt-10 py-10">
                <x-form.button :link="route('series.create')" type="submit" text="Create new series"/>
            </div>
        @endforelse
        <div class="py-10">
            {{ $series->links() }}
        </div>
    </ul>
@endsection
