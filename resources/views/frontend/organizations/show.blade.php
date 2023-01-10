@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-32 h-auto md:mt-32">
        <div class="flex flex-col justify-center content-center items-center place-content-center">
            <h2 class="text-2xl font-bold">{{ $organization->name }} Series index</h2>
        </div>
        <ul class="flex-row">
            <div class="grid grid-cols-4 gap-4">
                @forelse($orgSeries as $singleSeries)
                    <li class="w-full p-4 bg-white my-2 rounded ">
                        @include('backend.series._card',['series'=> $singleSeries])
                    </li>
                @empty
                    <li class="w-full p-4 bg-white my-2 rounded">
                        Portal has no series yet!
                    </li>
                @endforelse
            </div>

            <div class="py-10">
                {{ $orgSeries->links() }}
            </div>
        </ul>
    </main>
@endsection
