@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Clips index
    </div>
    <div class="grid 2xl:grid-cols-4  xl:grid-cols-3 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-6">
        @forelse($clips as $clip)
            @include('backend.clips._card',['clip'=> $clip])
        @empty
            No more clips found
        @endforelse
    </div>

    <div class="flex pt-4">
        <x-form.button :link="route('clips.create')"
                       type="submit"
                       text="Create new clip"/>
    </div>
    <div class="py-10">
        {{ $clips->links() }}
    </div>
@endsection

