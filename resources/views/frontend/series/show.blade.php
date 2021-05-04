@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-32 h-screen md:mt-32">
        <div class="flex justify-between pb-2 border-b-2 border-black">
            <h2 class="text-2xl font-bold">{{ $series->title }} [ID: {{ $series->id }}]</h2>
            @can('edit-series', $series)
                <a  class="focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-green-700 hover:bg-green-600 hover:shadow-lg"
                    href="{{ $series->adminPath() }}"
                > Back to edit page </a>
            @endcan
        </div>

        <div class="flex flex-col pt-20">
            <h2 class="text-2xl font-semibold">Description</h2>
            <p class="pt-4">
                {{ $series->description }}
            </p>
        </div>

        @include('backend.clips.list')
    </main>
@endsection
