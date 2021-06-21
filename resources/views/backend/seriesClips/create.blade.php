@extends('layouts.backend')

@section('content')
        <div class="flex pb-2 font-semibold border-b border-black font-2xl">
            Add clip to {{ $series->title }}
        </div>
        <div class="flex justify-center content-center py-2 px-2">
            <form action="{{route('series.clip.store', $series)}}"
                  method="POST"
                  class="w-4/5">
                @csrf
                <div class="grid grid-cols-8 gap-2 py-3">

                    <div class="flex content-center items-center">
                        <label class="block py-2 mr-6 font-bold text-gray-700 text-md"
                               for="episode"
                        >
                            Episode
                        </label>
                    </div>
                    <div class="col-span-7 w-20">
                        <input class="py-2 px-4 w-full leading-tight text-gray-700 bg-white rounded border-2
                                        border-gray-200 appearance-none focus:outline-none focus:bg-white
                                        focus:border-blue-500"
                               type="number"
                               name="episode"
                               id="episode"
                               value="{{ $series->clips()->count()+1 }}"
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
                        <input class="py-2 px-4 w-full leading-tight text-gray-700 bg-white rounded border-2
                                        border-gray-200 appearance-none focus:outline-none focus:bg-white
                                        focus:border-blue-500"
                               type="text"
                               name="title"
                               id="title"
                               value="{{ old('title') }}"
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
                        <textarea class="py-2 px-4 w-full leading-tight text-gray-700 bg-white rounded border-2
                                            border-gray-200 appearance-none focus:outline-none focus:bg-white
                                            focus:border-blue-500"
                                  type="text"
                                  name="description"
                                  id="description"
                        >{{ old('description') }} </textarea>
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
                        <select class="select2-tags-multiple p-2 w-full"
                                name="tags[]"
                                style="width: 100%"
                                multiple="multiple"
                        >
                        </select>
                    </div>
                    @error('tags')
                    <div class="col-span-8">
                        <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                    </div>
                    @enderror

                    <x-form.acl/>

                </div>
                <x-form.button :link="$link=false" type="submit" text="Add a Clip to Series"/>

            </form>
        </div>
    <div class="pt-10 lg:flex-1 lg:mx-10">

    </div>
    </main>
@endsection
