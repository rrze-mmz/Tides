@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Video file in drop zone
    </div>

    <div class="flex py-2">
        <x-form.button :link="$clip->adminPath()"
                       type="submit"
                       text="Go back to clip"
        />
    </div>
    <form action="{{ route('admin.clips.dropzone.transfer', $clip) }}"
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
                @forelse($files as $hash=>$file)
                    <option value="{{ $hash }}">{{ $file['name'] }}</option>
                @empty
                    no videos
                @endforelse
                @error('files')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <input type="submit"
               value="submit"
               class="mt-3 py-2 px-8  focus:outline-none text-white rounded-md bg-blue-700 hover:bg-blue-600
                            hover:shadow-lg"
        />
    </form>
@endsection
