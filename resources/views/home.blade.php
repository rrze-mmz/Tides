@extends('layouts.app')

@section('content')
<div class="w-full bg-gray-900 h-72 flex justify-center justify-items-center place-content-center place-items-center">
    <div class="font-bold text-white text-6xl">
        <h2>Welcome to Tides Videoportal</h2>
    </div>
</div>
<main class="sm:container sm:mx-auto sm:mt-10">
            @include('homepage._searchbar')

           <div class="flex justify-content-between  w-full items-end border-b">
               <div class="flex justify-between items-end w-full pb-2">
                   <div class="text-2xl ">Recently added </div>
                   <a href="/clips" class="text-sm underline">More clips</a>
               </div>

           </div>
            <div class="h48 grid grid-cols-3 gap-4 pt-8">
                @forelse($latestClips as $clip)
                    <div class=" w-full flex bg-white">
                        <div class="h-24 w-48 pt-3 ml-2 place-content-center place-items-center justify-center justify-items-center">
                            <img src="/images/preview.jpeg" alt="preview image">
                        </div>

                        <div class=" bg-white p-4 flex flex-col justify-between w-full ">
                            <div class="mb-1">
                                <div class="text-gray-900 font-bold text-sm"><a href="{{ $clip->path() }}" class="underline">{{ $clip->title }}</a></div>
                                <p class="text-gray-700 text-base pt-3">{{ Str::limit($clip->description, 30) }}</p>
                            </div>
                            <div class="flex items-center">
                                <div class="text-sm">
                                    <p class="text-gray-900">John Smith</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        No clips found
                    @endforelse
                </div>
</main>
@endsection
