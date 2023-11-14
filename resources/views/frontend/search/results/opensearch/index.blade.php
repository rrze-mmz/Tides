@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto md:mt-24">
        <div class="pb-10">
            @include('frontend.search._searchbar')
        </div>
        @if(isset($searchResults['series']['hits']))
            <div class="flex justify-between border-b-2 border-black pb-2">
                <h2 class="text-2xl font-bold">
                    {{ __('search.search series results header', [
                                'counter' => $searchResults['series']['counter']
                                ]) }}
                </h2>
            </div>
            <div class="mx-2 flex flex-col pt-2">
                @forelse($searchResults['series']['hits']['hits'] as $series)
                    @include('frontend.search.results.opensearch.series._elastic_series_card',
                                ['series'  => ($series['_source'])])
                @empty
                    {{ __('search.no series results found ') }}
                @endforelse
            </div>
        @else
            <div class="flex justify-between border-b-2 border-black pb-2">
                <h2 class="text-2xl font-bold">
                    {{ __('search.no series results found') }}
                </h2>
            </div>

        @endif

        {{--        @if(isset($searchResults['clips']['hits']))--}}
        {{--            <div class="flex justify-between border-b-2 border-black pb-2">--}}
        {{--                <h2 class="text-2xl font-bold">--}}
        {{--                    {{ __('search.search results header', [--}}
        {{--                                'counter' => $searchResults['clips']['counter']--}}
        {{--                                ]) }}--}}
        {{--                </h2>--}}
        {{--            </div>--}}
        {{--            <div class="mx-2 flex flex-col pt-2">--}}
        {{--                @forelse($searchResults['clips']['hits']['hits'] as $clip)--}}
        {{--                    @include('frontend.search.results._elastic_clip_card',['clip'  => collect($clip['_source'])])--}}
        {{--                @empty--}}
        {{--                    {{ __('search.no results found') }}--}}
        {{--                @endforelse--}}
        {{--            </div>--}}
        {{--        @else--}}
        {{--            <div class="flex justify-between border-b-2 border-black pb-2">--}}
        {{--                <h2 class="text-2xl font-bold">--}}
        {{--                    {{ __('search.no results found') }}--}}
        {{--                </h2>--}}
        {{--            </div>--}}

        {{--        @endif--}}
    </main>
@endsection
