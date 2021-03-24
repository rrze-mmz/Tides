@extends('layouts.backend')

@section('content')
        <div class="pt-10 w-full lg:flex-grow lg:mx-10">
            <div class="flex pb-2 font-semibold border-b border-black font-2xl">
                Edit {{ $clip->title }}
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
                <div class="py-4 px-4 mx-4 w-1/5 h-full bg-white rounded border">
                    <header class="items-center pb-2 mb-2 font-semibold text-center border-b"> Upload a Video </header>
                    <form action="{{ $clip->adminPath().'/assets' }}"
                          enctype="multipart/form-data"
                          method="POST"
                          class="flex flex-col"
                    >
                        @csrf
                        <input type="file" id="asset" name="asset">
                        <div class="flex pt-4 align-content-between items-center justify-content-center">
                            <legend>Convert to HLS?</legend>
                            <input class="mx-auto"
                                   type="checkbox"
                                   name="should_convert_to_hls"
                                   value="0"
                            >
                        </div>

                        <button type="submit"
                                class=" mt-3 ml-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-green-500 hover:bg-blue-600 hover:shadow-lg"
                        >Upload</button>
                        @error('asset')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </form>
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

                    <form action="{{ $clip->adminPath() }}"
                          method="POST"
                            class="" >
                        @csrf
                        @method('DELETE')
                    <button
                       type="submit"
                       class="p-3 text-sm text-white bg-red-700 rounded-md focus:outline-none hover:bg-red-500 hover:shadow-lg">
                        Delete
                    </button>

                    </form>
            </div>

            <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
                Assets
            </div>
            <div class="flex">
                <ul class="pt-3 w-full">
                    <li class="flex content-center items-center p-5 mb-4 text-xl bg-gray-400 rounded">
                        <div class="pb-2 w-1/5 border-b border-black">ID</div>
                        <div class="pb-2 w-3/5 border-b border-black">Saved path</div>
                        <div class="pb-2 w-1/5 border-b border-black">Resolution</div>
                        <div class="pb-2 w-1/5 border-b border-black">Actions</div>
                    </li>

                    @forelse($clip->assets as $asset)
                        <li class="flex content-center items-center p-5 mb-4 text-xl bg-gray-200 rounded">
                            <div class="w-1/5"> {{ $asset->id }}</div>
                            <div class="w-3/5"> {{ $asset->path }}</div>
                            <div class="w-1/5"> {{ $asset->width }} x {{ $asset->height }}</div>
                            <div class="w-1/5">
                                <form method="POST"
                                    action="{{$asset->path() }}"
                                >
                                    @csrf
                                    @method('DELETE')
                                 <button type="submit"
                                         class="focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-red-700 hover:bg-red-500 hover:shadow-lg"> Delete </button>
                                </form>
                            </div>
                        </li>
                    @empty
                        No assets
                    @endforelse
                </ul>
            </div>
        </div>
@endsection
