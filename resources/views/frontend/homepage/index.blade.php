@extends('layouts.frontend')

@section('content')

    <section class="bg-white dark:bg-slate-600">
        <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
                Welcome to Tides Videoportal</h1>
            <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 lg:px-48 dark:text-gray-200">
                Tides is an open source video platform based on the Laravel framework. It provides a flexible and
                customizable solution for managing and streaming videos.</p>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
                <a href="{{route('frontend.series.index')}}"
                   class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                    Explore recent videos from academia
                    <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg>
                </a>
                <a href="#"
                   class="py-3 px-5 sm:ms-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-70">
                    Learn more
                </a>
            </div>
        </div>
    </section>
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
