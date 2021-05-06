@extends('layouts.backend')

@section('content')
    <div class="pt-10 w-full lg:flex-grow lg:mx-10">
        <div class="flex pb-2 font-semibold border-b border-black font-2xl">
            Edit {{ $series->title }} [ ID : {{ $series->id }}]<span
                class="text-xs italic pl-2 pt-1"> created at {{$series->created_at }} </span>
        </div>
        <div class="flex justify-center content-center content-between py-2 px-2">
            <form action="{{ $series->adminPath() }}"
                  method="POST"
                  class="w-4/5">
                @csrf
                @method('PATCH')

                <div class="mb-6">
                    <label class="block mb-2 text-xs font-bold text-gray-700 uppercase"
                           for="title"
                    >
                        Title
                    </label>

                    <input class="p-2 w-full border border-gray-400"
                           type="text"
                           name="title"
                           id="title"
                           value="{{ $series->title }}"
                           required
                    >

                    @error('Title')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-xs font-bold text-gray-700 uppercase"
                           for="description"
                    >
                        Description
                    </label>

                    <textarea class="p-2 w-full border border-gray-400"
                              type="text"
                              name="description"
                              id="description"
                              required
                    > {{ $series->description  }}</textarea>

                    @error('description')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-xs font-bold text-gray-700 uppercase"
                           for="title"
                    >
                        Opencast Series ID
                    </label>

                    <input class="p-1 w-full border border-gray-400"
                           type="text"
                           name="title"
                           id="title"
                           value="{{ $series->opencast_series_id }}"
                           disabled
                    >

                    @error('Title')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="ml-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600 hover:shadow-lg"
                >Update a Series
                </button>
            </form>
        </div>

        <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
            More actions
        </div>
        <div class="flex items-center pt-3 space-x-6">
            <form action="{{ route('frontend.series.show',$series) }}"
                  method="GET">
                <button
                    class="ml-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-blue-900 hover:bg-blue-600 hover:shadow-lg">
                    Go to public page
                </button>
            </form>
            <form action="{{ route('series.clip.create',$series) }}"
                  method="GET">
                <button
                    class="ml-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-blue-900 hover:bg-blue-600 hover:shadow-lg">
                    Add new clip
                </button>
            </form>
            <form action="{{$series->adminPath()}}"
                  method="POST">
                @csrf
                @method('DELETE')
                <button
                    class="ml-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-red-900 hover:bg-red-600 hover:shadow-lg">
                    Delete Series
                </button>
            </form>
        </div>

        @include('backend.clips.list')

    </div>
@endsection
