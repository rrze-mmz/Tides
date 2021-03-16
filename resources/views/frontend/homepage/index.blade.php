@extends('layouts.frontend')

@section('content')
<div class="flex justify-center justify-items-center place-content-center place-items-center w-full h-72 bg-gray-900">
    <div class="text-6xl font-bold text-white">
        <h2>Start by creating something new</h2>
    </div>
</div>
<main class="sm:container sm:mx-auto sm:mt-16">
            @include('frontend.search._searchbar')

           <div class="flex items-end w-full border-b justify-content-between">
               <div class="flex justify-between items-end pb-2 w-full">
                   <div class="text-2xl">Recently added </div>
                   <a href="/clips" class="text-sm underline">More clips</a>
               </div>

           </div>
            <div class="grid grid-cols-3 gap-4 pt-8 h48">
                @forelse($clips as $clip)
                    @include('backend.clips._card',[
                            'clip'=> $clip,
                            'route' => 'admin'
                            ])
                    @empty
                        No clips found
                    @endforelse
                </div>
</main>
@endsection
