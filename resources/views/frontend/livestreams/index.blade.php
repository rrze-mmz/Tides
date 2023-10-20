@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-6 md:mt-12">
        <div class="flex items-center border-b-2 border-black pb-2">
            <div class="flex-grow">
                <h2 class="text-2xl font-bold">Active livestreams</h2>
            </div>
        </div>
        @forelse ($livestreams as $livestream)

        @empty
            <div class="pt-10">
                No active livestreams found atm
            </div>
        @endforelse
    </main>
@endsection
