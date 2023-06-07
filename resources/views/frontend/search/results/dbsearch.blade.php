@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto md:mt-24">
        <div class="flex justify-between border-b-2 border-black pb-2">
            <h2 class="text-2xl font-bold">
                {{ __('search.search results header', ['counter' => $searchResults['clips']->total()]) }}
            </h2>
        </div>
        <div class="mx-2 flex flex-col pt-2">
            @forelse($searchResults['clips'] as $clip)
                @include('backend.clips._card',['clip'  => $clip,])
            @empty
                {{ __('search.no results found') }}
            @endforelse
        </div>
        {{ $searchResults['clips']->links() }}
    </main>
@endsection
