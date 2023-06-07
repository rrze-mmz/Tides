@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 font-semibold font-2xl">
        Select series for: {{ $clip->title }}
    </div>
    <ul class="flex flex-col">
        @forelse($series as $singleSeries)
            <li class="my-2 w-full rounded bg-white p-4">
                @include('backend.series._card',['series'=> $singleSeries,'action'=>'assignClip'])
            </li>
        @empty
            <li class="my-2 w-full rounded bg-white p-4">
                You have no series yet...Maybe create one and the assign it?
            </li>

            <div class="py-10 pt-10">
                <x-form.button :link="route('series.create')" type="submit" text="Create new series"/>
            </div>
        @endforelse
        <div class="py-10">
            {{ $series->links() }}
        </div>
    </ul>
@endsection
