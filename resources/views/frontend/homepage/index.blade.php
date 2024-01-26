@extends('layouts.frontend')

@section('content')
    <div
        class="flex h-72 w-full place-content-center place-items-center justify-center
        justify-items-center bg-gray-900 dark:bg-gray-700">
        <div class="text-6xl font-bold text-white">
            <h2>{{__('homepage.Start by creating something new')}}</h2>
        </div>
    </div>
    <main class="sm:container sm:mx-auto sm:mt-16">
        @include('frontend.search._searchbar')

        @auth()
            @if(auth()->user()->settings->data['show_subscriptions_to_home_page'])
                <div class="flex w-full items-end border-b-2 border-black dark:border-white justify-content-between">
                    <div class="flex w-full items-end justify-between pb-2 border-b-2 border-black dark:border-white">
                        <div class="text-2xl"> {{ __('homepage.series.Your series subscriptions') }}</div>
                        <a href="{{ route('frontend.series.index') }}"
                           class="text-sm underline">{{__('homepage.series.more series') }}</a>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4">
                    @forelse(auth()->user()->subscriptions as $single)
                        @include('backend.series._card',[
                                'series'=> $single,
                                'route' => 'admin'
                                ])
                    @empty
                        <div class="dark:text-white text-2xl">
                            {{ __('homepage.series.You are not subscribed to any series') }}
                        </div>
                    @endforelse
                </div>
            @endif
        @endauth

        <div class="flex w-full items-end border-b justify-content-between pb-4
            border-b-2 border-black dark:border-white "
        >
            <div class="flex w-full items-end justify-between pb-2">
                <div class="text-2xl dark:text-white">  {{  __('homepage.series.Recently added!') }} </div>
                <a href="{{ route('frontend.series.index') }}"
                   class="text-sm underline dark:text-white ">{{__('homepage.series.more series') }}</a>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4 pt-8 border-b-2 border-black dark:border-white ">
            @forelse($series as $single)

                @include('backend.series._card',[
                        'series'=> $single,
                        'route' => 'admin'
                        ])
            @empty
                <div class="dark:text-white text-2xl pt-10">
                    {{ __('homepage.series.no series found' )}}
                </div>
            @endforelse
        </div>


        <div class="flex w-full items-end border-b justify-content-between pt-10 pb-4">
            <div class="flex w-full items-end justify-between pb-2">
                <div class="text-2xl dark:text-white"> {{  __('homepage.clips.Recently added!') }}</div>
                <a href="{{ route('frontend.clips.index') }}"
                   class="text-sm underline dark:text-white">{{__('homepage.clips.more clips')}}</a>
            </div>

        </div>
        <ul class="flex-row">
            <div class="grid grid-cols-4 gap-6 pt-8">
                @forelse($clips as $clip)
                    <li class="my-2 w-full rounded bg-white dark:bg-gray-900 p-4">
                        @include('backend.clips._card',[
                                'clip'=> $clip,
                                'route' => 'admin'
                                ])
                    </li>
                @empty
                    <div class="dark:text-white text-2xl">
                        {{ __('homepage.clips.no clips found' )}}
                    </div>

                @endforelse
            </div>
        </ul>
    </main>
@endsection
