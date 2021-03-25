@extends('layouts.backend')

@section('content')
    <div class="pt-10 w-full lg:flex-grow lg:mx-10">
        <div class="flex pb-2 font-semibold border-b border-black font-2xl">
            Video file in drop zone
        </div>
        <div class="grid grid-cols-3 gap-4 pt-8 h48">
            @forelse($files as $file)
                {{ $file['name'] }}
            @empty
                no videos
            @endforelse
        </div>
    </div>
@endsection
