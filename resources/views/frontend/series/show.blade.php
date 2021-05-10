@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-32 h-screen md:mt-32">
        <div class="flex justify-between pb-2 border-b-2 border-black">
            <h2 class="text-2xl font-bold">{{ $series->title }} [ID: {{ $series->id }}]</h2>
            @can('edit-series', $series)
                <a  class="mt-2 py-2 px-8 text-white bg-blue-500 rounded shadow hover:bg-blue-600 focus:shadow-outline focus:outline-none"
                    href="{{ $series->adminPath() }}"
                > Edit series </a>
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
