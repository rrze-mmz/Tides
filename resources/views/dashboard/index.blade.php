@extends('layouts.app')

@section('content')
    <main class="mx-auto lg:flex pt-12">
            <div class="flex-none justify-center bg-gray-800 content-center h-screen w-1/7 ">
                    @include('dashboard._sidebar-navigation')
            </div>
            <div class="lg:flex-grow lg:mx-10 pt-10 w-full">
                <div class="flex font-2xl font-semibold border-b border-black pb-2 ">
                    Welcome to your personal Dashboard {{ auth()->user()->name }} !!
                </div>
                <div class="flex px-2 py-2">
                    <p>
                        Start by creating a new video clip
                        <a class="ml-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600 hover:shadow-lg"
                           href="{{ route('clips.create') }}"
                        >New clip</a>
                    </p>

                </div>
                <div class="pt-10 font-2xl font-semibold border-b border-black pb-2 ">
                   Your Latest Clips
                </div>
                <div class="h48 grid grid-cols-3 gap-4 pt-8">
                    @forelse($clips as $clip)
                        @include('clips._card',['clip'=> $clip])
                    @empty
                        No clips found
                    @endforelse
                </div>
            </div>
        <div class="lg:flex-1 lg:mx-10 pt-10">

        </div>
    </main>
@endsection
