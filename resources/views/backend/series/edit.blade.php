@extends('layouts.backend')

@section('content')
        <div class="flex pb-2 font-semibold border-b border-black font-2xl">
            Edit {{ $series->title }} [ ID : {{ $series->id }}]<span
                class="text-xs italic pl-2 pt-1"> created at {{$series->created_at }} </span>
        </div>
        <div class="flex justify-center content-center content-between py-2 px-2">
            <form action="{{ $series->adminPath() }}"
                  method="POST"
                  class="w-4/5 w-full"
            >
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-8 gap-2 py-3">

                    <div class="flex content-center items-center">
                        <label class="block py-2 mr-6 font-bold text-gray-700 text-md"
                               for="title"
                        >
                            Title
                        </label>
                    </div>
                    <div class="col-span-7 w-4/5">
                        <input class="py-2 px-4 w-full leading-tight text-gray-700 bg-white rounded border-2 border-gray-200 appearance-none focus:outline-none focus:bg-white focus:border-blue-500"
                               type="text"
                               name="title"
                               id="title"
                               value="{{ $series->title }}"
                               required
                        >
                    </div>
                    @error('title')
                    <div class="col-span-8">
                        <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                    </div>
                    @enderror

                    <div class="flex content-center items-center mb-6">
                        <label class="block py-2 mr-6 font-bold text-gray-700 text-md"
                               for="description"
                        >
                            Description
                        </label>
                    </div>
                    <div class="col-span-7 w-4/5">
                        <textarea class="py-2 px-4 w-full leading-tight text-gray-700 bg-white rounded border-2 border-gray-200 appearance-none focus:outline-none focus:bg-white focus:border-blue-500"
                                  type="text"
                                  name="description"
                                  id="description"
                        > {{ $series->description }}</textarea>
                    </div>
                    @error('description')
                    <div class="col-span-8">
                        <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                    </div>
                    @enderror

                    <div class="flex content-center items-center">
                        <label class="block py-2 mr-6 font-bold text-gray-700 text-md"
                               for="opencast_series_id"
                        >
                            Opencast Series ID
                        </label>
                    </div>
                    <div class="col-span-7 w-4/5">
                        <input class="py-2 px-4 w-full leading-tight text-gray-700 bg-white rounded border-2 border-gray-200 appearance-none focus:outline-none focus:bg-white focus:border-blue-500"
                               type="text"
                               name="opencast_series_id"
                               id="opencast_series_id"
                               disabled
                               value="{{ $series->opencast_series_id }}"
                               required
                        >
                    </div>
                    @error('opencast_series_id')
                    <div class="col-span-8">
                        <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                    </div>
                    @enderror

                </div>
                <button type="submit"
                        class="py-2 px-8 text-white bg-blue-500 rounded shadow hover:bg-blue-600 focus:shadow-outline focus:outline-none"
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
                    class="py-2 px-8  text-white bg-blue-500 rounded shadow hover:bg-blue-600 focus:shadow-outline focus:outline-none">
                    Go to public page
                </button>
            </form>
            <form action="{{ route('series.clip.create',$series) }}"
                  method="GET">
                <button
                    class="py-2 px-8  text-white bg-blue-500 rounded shadow hover:bg-blue-600 focus:shadow-outline focus:outline-none">
                    Add new clip
                </button>
            </form>
            <form action="{{$series->adminPath()}}"
                  method="POST">
                @csrf
                @method('DELETE')
                <button
                    class="py-2 px-8  text-white bg-red-700 rounded shadow hover:bg-red-600 focus:shadow-outline focus:outline-none">
                    Delete Series
                </button>
            </form>
        </div>
        @if(isset($opencastSeriesRunningWorkflows['workflows']) && $opencastSeriesRunningWorkflows['workflows']['totalCount'] > 0)
        <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
            Opencast running events
        </div>
            <ul>
                @foreach($opencastSeriesRunningWorkflows['workflows']['workflow'] as $workflow)
                    <li>
                        {{ $workflow['mediapackage']['title'] }}
                    </li>
                @endforeach
            </ul>
        @endif
        @include('backend.clips.list')



    </div>
@endsection
