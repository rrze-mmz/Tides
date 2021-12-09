@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto md:mt-24">
        <div class="flex justify-between pb-2 border-b-2 border-black">
            <h2 class="text-2xl font-bold">
                Search results: found {{ $searchResults['clips']['hits']['total']['value'] . ' Clips'}}
            </h2>
        </div>
        <div class="flex flex-col pt-2 mx-2">
            @forelse($searchResults['clips']['hits']['hits'] as $clip)
                @include('frontend.search.results._elastic_clip_card',['clip'  => collect($clip['_source'])])
            @empty
                No results found
            @endforelse
        </div>
    </main>
@endsection
