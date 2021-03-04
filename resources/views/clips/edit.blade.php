@extends('layouts.app')

@section('content')
    <main class="mx-auto lg:flex pt-12">
        <div class="flex-none justify-center bg-gray-800 content-center h-screen w-1/7 ">
            @include('dashboard._sidebar-navigation')
        </div>
        <div class="lg:flex-grow lg:mx-10 pt-10 w-full">
            <div class="flex font-2xl font-semibold border-b border-black pb-2 ">
                Edit {{ $clip->title }} test
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
                    >Update</button>
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
                        <input type="submit"
                                class=" mt-3 ml-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-green-500 hover:bg-blue-600 hover:shadow-lg">
                        @error('uploadedFile')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
            </div>

            <div class="flex font-2xl font-semibold border-b border-black pb-2 pt-8 ">
                Assets
            </div>
            <div class="flex">
                <ul class="pt-3 ">
                    @forelse($clip->assets as $asset)
                        <li>
                            {{ $asset->uploadedFile }}
                        </li>
                    @empty
                        No assets
                    @endforelse
                </ul>
            </div>
        </div>

    </main>
@endsection
