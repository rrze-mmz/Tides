@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Series index
    </div>
    <ul class="flex-row">
        <div class="grid grid-cols-6 gap-6">
            @forelse($series as $singleSeries)
                @include('backend.series._card',['series'=> $singleSeries])
            @empty
                <li class="w-full p-4 bg-white my-2 rounded">
                    No more series found
                </li>
                <a href="{{route('series.create')}}">Create new series</a>
            @endforelse
        </div>


        <div class="pt-10 py-10">
            <x-form.button :link="route('series.create')" type="submit" text="Create new series"/>
        </div>
        
        <div class="flex py-10">
            {{ $series->links() }}
        </div>
    </ul>
@endsection

