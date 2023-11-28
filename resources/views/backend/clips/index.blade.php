@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 font-semibold font-2xl">
        Clips index
    </div>
    <livewire:clips-data-table />
    {{--    <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4">--}}
    {{--        @forelse($clips as $clip)--}}
    {{--            @include('backend.clips._card',['clip'=> $clip])--}}
    {{--        @empty--}}
    {{--            No more clips found--}}
    {{--        @endforelse--}}
    {{--    </div>--}}

    {{--    <div class="flex pt-4">--}}
    {{--        <x-form.button :link="route('clips.create')"--}}
    {{--                       type="submit"--}}
    {{--                       text="Create new clip" />--}}
    {{--    </div>--}}
    {{--    <div class="py-10">--}}
    {{--        {{ $clips->links() }}--}}
    {{--    </div>--}}
@endsection

