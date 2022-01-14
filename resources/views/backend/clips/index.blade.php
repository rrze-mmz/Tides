@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Clips index
    </div>
    <div class="grid grid-cols-3 gap-4 pt-8 h48">
        @forelse($clips as $clip)
            @include('backend.clips._card',['clip'=> $clip])
        @empty
            No clips found
            <div class="pt-10 py-10">
                <x-form.button :link="route('clips.create')" type="submit" text="Create new clip"/>
            </div>
        @endforelse
    </div>

    <div class="py-10">
        {{ $clips->links() }}
    </div>
@endsection

