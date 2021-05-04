@extends('layouts.backend')

@section('content')
        <div class="pt-10 w-full lg:flex-grow lg:mx-10">
            <div class="flex pb-2 font-semibold border-b border-black font-2xl">
                Edit {{ $clip->title }} [ ID: {{ $clip->id }} ] <span class="font-sm italic pl-2"> created at {{$clip->created_at}}</span>
            </div>
            <div class="flex justify-center content-center content-between py-2 px-2">
                <form action="{{ $clip->adminPath() }}"
                      method="POST"
                      class="w-4/5"
                >
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
                               value="{{ $clip->title }}"
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
                        > {{ $clip->description }}</textarea>

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
                                multiple="multiple"
                                style="width: 100%"
                        >
                            @forelse($clip->tags as $tag)
                                <option value="{{ $tag->name }}" selected="selected">{{ $tag->name }}</option>
                            @empty
                                <option value="1"></option>
                            @endforelse
                        </select>
                    </div>

                    <button type=""
                            class="ml-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600 hover:shadow-lg"
                    >Save</button>
                </form>
                <div class=" w-1/5 h-full space-y-5">
                    @if(! is_null($clip->series_id) )
                        @include('backend.clips.sidebar._series-options')
                    @endif

                        @include('backend.clips.sidebar._upload-video')
                    </div>
            </div>

            <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
                More actions
            </div>
            <div class="flex items-center pt-3 space-x-6">
                    <a href="{{ $clip->path() }}"
                       type="button"
                        class="p-3 text-sm text-white bg-blue-500 rounded-md focus:outline-none hover:bg-blue-600 hover:shadow-lg">
                        Go to view page
                    </a>

                    <a href="{{ route('admin.clips.dropzone.listFiles', $clip) }}"
                       type="button"
                       class="p-3 text-sm text-white bg-blue-500 rounded-md focus:outline-none hover:bg-blue-600 hover:shadow-lg">
                        Transfer files from drop zone
                    </a>

                    <form action="{{ $clip->adminPath() }}"
                          method="POST"
                        >
                        @csrf
                        @method('DELETE')
                    <button
                       type="submit"
                       class="p-3 text-sm text-white bg-red-700 rounded-md focus:outline-none hover:bg-red-500 hover:shadow-lg">
                        Delete
                    </button>

                    </form>
            </div>

            @include('backend.assets.list', ['assets'=>$clip->assets])
        </div>
@endsection
