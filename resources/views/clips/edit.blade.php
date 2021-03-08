@extends('layouts.backend')

@section('content')
        <div class="lg:flex-grow lg:mx-10 pt-10 w-full">
            <div class="flex font-2xl font-semibold border-b border-black pb-2 ">
                Edit {{ $clip->title }}
            </div>
            <div class="flex content-between  px-2 py-2 content-center justify-center">
                <form action="{{ $clip->adminPath() }}"
                      method="POST"
                      class="w-4/5"
                >
                    @csrf
                    @method('PATCH')
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                               for="title"
                        >
                            Title
                        </label>

                        <input class="border border-gray-400 p-2 w-full"
                               type="text"
                               name="title"
                               id="title"
                               value="{{ $clip->title }}"
                               required
                        >

                        @error('title')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                               for="description"
                        >
                            Description
                        </label>

                        <textarea class="border border-gray-400 p-2 w-full"
                                  type="text"
                                  name="description"
                                  id="description"
                        > {{ $clip->description }}</textarea>

                        @error('description')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type=""
                            class="ml-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600 hover:shadow-lg"
                    >Save</button>
                </form>
                <div class="w-1/5 px-4 border rounded mx-4 h-full  py-4 bg-white ">
                    <header class="font-semibold pb-2 items-center text-center border-b mb-2 "> Upload a file </header>
                    <form action="{{ $clip->adminPath().'/assets' }}"
                          enctype="multipart/form-data"
                          method="POST"
                          class="flex flex-col"
                    >
                        @csrf
                        <input type="file" id="uploadedFile" name="uploadedFile">
                        <button type="submit"
                                class=" mt-3 ml-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-green-500 hover:bg-blue-600 hover:shadow-lg"
                        >Upload</button>
                        @error('uploadedFile')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
            </div>

            <div class="flex font-2xl font-semibold border-b border-black pb-2 pt-8">
                More actions
            </div>
            <div class="flex space-x-6 items-center pt-3">
                    <a href="{{ $clip->path() }}"
                       type="button"
                        class="focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600 hover:shadow-lg">
                        Go to view page
                    </a>

                    <form action="{{ $clip->adminPath() }}"
                          method="POST"
                            class="" >
                        @csrf
                        @method('DELETE')
                    <button
                       type="submit"
                       class="focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-red-700 hover:bg-red-500 hover:shadow-lg">
                        Delete
                    </button>

                    </form>
            </div>

            <div class="flex font-2xl font-semibold border-b border-black pb-2 pt-8">
                Assets
            </div>
            <div class="flex ">
                <ul class="pt-3 ">
                    @forelse($clip->assets as $asset)
                        <li class="flex space-x-6 items-center pt-3">
                            <div>
                                {{ $asset->uploadedFile }} [AssetID: {{ $asset->id }}]
                            </div>

                            <form method="POST"
                                action="{{$asset->path() }}"
                            >
                                @csrf
                                @method('DELETE')
                             <button type="submit"
                                     class="focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-red-700 hover:bg-red-500 hover:shadow-lg"> Delete </button>
                            </form>
                        </li>
                    @empty
                        No assets
                    @endforelse
                </ul>
            </div>
        </div>
@endsection
