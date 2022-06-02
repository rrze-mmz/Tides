@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Series index
    </div>
    <ul class="flex-row">
        <div class="grid grid-cols-3 gap-4">
            @forelse($series as $singleSeries)
                @include('backend.series._card',['series'=> $singleSeries])
            @empty
                <li class="w-full p-4 bg-white my-2 rounded">
                    No more series found
                </li>
                <a href="{{route('clips.create')}}">Create new series</a>
            @endforelse
        </div>

        <div class="flex py-10">
            {{ $series->links() }}
        </div>
    </ul>
@endsection

