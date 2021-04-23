@extends('layouts.backend')

@section('content')
    <div class="pt-10 w-full lg:flex-grow lg:mx-10">
        <div class="flex pb-2 font-semibold border-b border-black font-2xl">
            Series index
        </div>
            <ul class="flex flex-col">
                @forelse($series as $singleSeries)
                        <li class="w-full p-4 bg-white my-2 rounded ">
                            @include('backend.series._card',['series'=> $singleSeries])
                        </li>
                @empty
                    <li class="w-full p-4 bg-white my-2 rounded">
                        You have no series yet
                    </li>
                @endforelse
            </ul>
    </div>
@endsection

