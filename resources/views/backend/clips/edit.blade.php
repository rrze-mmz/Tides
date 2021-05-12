@extends('layouts.backend')

@section('content')
            <div class="flex pb-2 font-semibold border-b border-black font-2xl">
                Edit {{ $clip->title }} [ ID: {{ $clip->id }} ] <span class="pl-2 italic font-sm"> created at {{$clip->created_at}}</span>
            </div>
            <div class="flex py-2 px-2">
                <form action="{{ $clip->adminPath() }}"
                      method="POST"
                      class="w-4/5 w-full"
                >
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-8 gap-2 py-3">

                        <div class="flex content-center items-center">
                            <label class="block py-2 mr-6 font-bold text-gray-700 text-md"
                                   for="episode"
                            >
                                Episode
                            </label>
                        </div>
                        <div class="col-span-7 w-20">
                            <input class="py-2 px-4 w-full leading-tight text-gray-700 bg-white rounded border-2 border-gray-200 appearance-none focus:outline-none focus:bg-white focus:border-blue-500"
                                   type="number"
                                   name="episode"
                                   id="episode"
                                   value="{{ $clip->episode }}"
                                   required
                            >
                        </div>
                        @error('episode')
                                <div class="col-span-8">
                                    <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                                </div>
                        @enderror

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
                                   value="{{ $clip->title }}"
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
                                   for="title"
                            >
                                Description
                            </label>
                        </div>
                        <div class="col-span-7 w-4/5">
                        <textarea class="py-2 px-4 w-full leading-tight text-gray-700 bg-white rounded border-2 border-gray-200 appearance-none focus:outline-none focus:bg-white focus:border-blue-500"
                                  type="text"
                                  name="description"
                                  id="description"
                        > {{ $clip->description }}</textarea>
                        </div>
                        @error('description')
                        <div class="col-span-8">
                            <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                        </div>
                        @enderror

                        <div class="flex content-center items-center mb-6">
                            <label class="block py-2 mr-6 font-bold text-gray-700 text-md"
                                   for="title"
                            >
                                Tags
                            </label>
                        </div>
                        <div class="col-span-7 w-4/5">
                            <select class="p-2 w-full js-example-basic-single"
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
                        @error('tags')
                        <div class="col-span-8">
                            <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                        </div>
                        @enderror

                    </div>

                    <button class="py-2 px-8 text-white bg-blue-500 rounded shadow hover:bg-blue-600 focus:shadow-outline focus:outline-none"
                            type="submit"
                            value="submit"
                    >
                        Save
                    </button>
                </form>
                <div class="space-y-5 w-1/5 h-full">
                    @if(! is_null($clip->series_id) )
                        @include('backend.clips.sidebar._series-options')
                    @endif

                    @include('backend.clips.sidebar._upload-video')

                    @if ($opencastConnectionCollection->isNotEmpty())
                        @include('backend.clips.sidebar._ingest-video')
                    @endif
                </div>
            </div>

            <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
                More actions
            </div>
            <div class="flex items-center pt-3 space-x-6">
                    <a href="{{ $clip->path() }}"
                       type="button"
                        class="py-2 px-8 text-white bg-blue-500 rounded shadow hover:bg-blue-600 focus:shadow-outline focus:outline-none">
                        Go to view page
                    </a>

                    <a href="{{ route('admin.clips.dropzone.listFiles', $clip) }}"
                       type="button"
                       class="py-2 px-8 text-white bg-blue-500 rounded shadow hover:bg-blue-600 focus:shadow-outline focus:outline-none">
                        Transfer files from drop zone
                    </a>

                    <form action="{{ $clip->adminPath() }}"
                          method="POST"
                        >
                        @csrf
                        @method('DELETE')
                    <button
                       type="submit"
                       class="py-2 px-8 text-white bg-red-500 rounded shadow hover:bg-red-600 focus:shadow-outline focus:outline-none">
                        Delete
                    </button>

                    </form>
            </div>

            @include('backend.assets.list', ['assets'=>$clip->assets])
@endsection
