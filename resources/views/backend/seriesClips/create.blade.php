@extends('layouts.backend')

@section('content')
    <div class="pt-10 w-full lg:flex-grow lg:mx-10">
        <div class="flex pb-2 font-semibold border-b border-black font-2xl">
            Add clip to {{ $series->title }}
        </div>
        <div class="flex justify-center content-center py-2 px-2">
            <form action="{{route('series.clip.store', $series)}}"
                  method="POST"
                  class="w-4/5">
                @csrf
                <div class="mb-6">
                    <label class="block mb-2 text-xs font-bold text-gray-700 uppercase"
                           for="episode"
                    >
                        Episode
                    </label>

                    <input class="p-2 w-full border border-gray-400"
                           type="number"
                           name="episode"
                           id="episode"
                           value="{{ $series->clips()->count()+1 }}"
                           required
                    >

                    @error('epidisode')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
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
                           value="{{ old('title') }}"
                           required
                    >

                    @error('title')
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
                    > {{ old('description') }}</textarea>

                    @error('description')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-xs font-bold text-gray-700 uppercase"
                           for="tags"
                    >
                        Tags
                    </label>
                    <select class="js-example-basic-single p-2 w-full"
                            name="tags[]"
                            style="width: 100%"
                            multiple="multiple"
                    >
                    </select>

                    @error('tags')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="ml-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600 hover:shadow-lg"
                >Add a Clip to Series</button>
            </form>
        </div>
    </div>
    <div class="pt-10 lg:flex-1 lg:mx-10">

    </div>
    </main>
@endsection
