@extends('layouts.backend')

@section('content')
    <div class="pt-10 w-full lg:flex-grow lg:mx-10">
        <div class="flex pb-2 font-semibold border-b border-black font-2xl">
            Video file in drop zone
        </div>

        <form action = "{{ route('admin.clips.dropzone.transfer', $clip) }}"
               method="POST"
                class="w-3/5">
            @csrf

            <div class="mb-6">
                <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                       for="files[]"
                >
                Please select video files
                </label>

                <select class="border border-gray-400 p-2 w-full"
                       type="text"
                       name="files[]"
                       multiple
                       id="dropzone_files"
                       required
                >
                    @forelse($files as $file)
                        <option value="{{ $file['hash'] }}">{{ $file['name'] }}</option>
                    @empty
                        no videos
                    @endforelse
                @error('files')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <input type="submit"
                   value="submit"
                    class="mt-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600 hover:shadow-lg"
            >
        </form>
    </div>
@endsection
