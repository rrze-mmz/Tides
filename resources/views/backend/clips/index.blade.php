@extends('layouts.backend')

@section('content')
        <div class="flex pb-2 font-semibold border-b border-black font-2xl">
           Clips index
        </div>
        <div class="grid grid-cols-3 gap-4 pt-8 h48">
            @forelse($clips as $clip)
                @include('backend.clips._card',['clip'=> $clip])
            @empty
                No clips found
            @endforelse
        </div>
@endsection

