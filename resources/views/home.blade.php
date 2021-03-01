@extends('layouts.app')

@section('content')
<div class="w-full bg-gray-900 h-52  flex justify-center justify-items-center place-content-center place-items-center">
    <div class="font-bold text-white text-6xl">
        <h2>Welcome to Tides Videoportal</h2>
    </div>
</div>
<main class="sm:container sm:mx-auto sm:mt-10">
        {{--        Search form--}}
        <div class="flex justify-center justify-center content-center">
            <form method="POST"
                action="/"
                class="w-3/5">
                @csrf
                <div class="p-2">
                    <div class="bg-white flex items-center rounded-full shadow-xl">
                        <input class="rounded-l-full w-full py-2 px-6 text-gray-700 leading-tight focus:outline-none"
                               id="search"
                               type="text"
                               placeholder="Search">

                        <div class="p-4">
                            <button class="bg-gray-600 text-white rounded-full p-2 hover:bg-gray-500 focus:outline-none w-8 h-8 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
       <div class="flex">
           <div>
               <h2 class="text-2xl font-bold pb-2 border-b ">Recently added </h2>
                @forelse($latestClips as $clip)
                            <div class="pt-3">
                                <a href="{{ $clip->path() }}">{{ $clip->title }}</a>
                            </div>
                   @empty
                    No clips found
               @endforelse
           </div>
       </div>
</main>
@endsection
