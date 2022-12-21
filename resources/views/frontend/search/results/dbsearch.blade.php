@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto md:mt-24">
        <div class="flex justify-between pb-2 border-b-2 border-black">
            <h2 class="text-2xl font-bold">
                {{ __('search.search results header', ['counter' => $searchResults['clips']->total()]) }}
            </h2>
        </div>
        <div class="flex flex-col pt-2 mx-2">
            @forelse($searchResults['clips'] as $clip)
                @include('backend.clips._card',['clip'  => $clip,])
            @empty
                {{ __('search.no results found') }}
            @endforelse
        </div>
        {{ $searchResults['clips']->links() }}
    </main>
@endsection
