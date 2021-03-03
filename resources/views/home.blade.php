@extends('layouts.app')

@section('content')
<div class="w-full bg-gray-900 h-72 flex justify-center justify-items-center place-content-center place-items-center">
    <div class="font-bold text-white text-6xl">
        <h2>Start by creating something new</h2>
    </div>
</div>
<main class="sm:container sm:mx-auto sm:mt-16">
            @include('homepage._searchbar')

           <div class="flex justify-content-between  w-full items-end border-b">
               <div class="flex justify-between items-end w-full pb-2">
                   <div class="text-2xl ">Recently added </div>
                   <a href="/clips" class="text-sm underline">More clips</a>
               </div>

           </div>
            <div class="h48 grid grid-cols-3 gap-4 pt-8">
                @forelse($clips as $clip)
                    @include('clips._card',[
                            'clip'=> $clip,
                            'route' => 'admin'
                            ])
                    @empty
                        No clips found
                    @endforelse
                </div>
</main>
@endsection
