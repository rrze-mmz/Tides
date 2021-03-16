@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-32 h-screen md:mt-32">
        <div class="flex justify-between pb-2 border-b-2 border-black">
            <h2 class="text-2xl font-bold">Search results: found {{ count($clips).' Clips'}}</h2>
        </div>
    @forelse($clips as $clip)
        @include('backend.clips._card',[
        'clip'  => $clip,
])
    @empty
        No  results found
    @endforelse
        </div>
    </main>
@endsection
