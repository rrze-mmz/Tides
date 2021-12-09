@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto md:mt-24">
        <div class="flex justify-between pb-2 border-b-2 border-black">
            <h2 class="text-2xl font-bold">Search results: found {{ count($searchResults['clips']).' Clips'}}</h2>
        </div>
        <div class="flex flex-col pt-2 mx-2">
            @forelse($searchResults['clips'] as $clip)
                @include('backend.clips._card',['clip'  => $clip,])
            @empty
                No results found
            @endforelse
        </div>
        {{ $searchResults['clips']->links() }}
    </main>
@endsection
