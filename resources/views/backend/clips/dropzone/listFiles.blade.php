@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 font-semibold font-2xl dark:text-white dark:border-white">
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
            <label class="mb-2 block text-xs font-bold uppercase text-gray-700 dark:text-white"
                   for="files[]"
            >
                Please select video files
            </label>

            <select class="w-full border border-gray-400 p-2"
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
                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <input type="submit"
               value="submit"
               class="mt-3 py-2 px-8 font-normal focus:outline-none text-white rounded-md bg-blue-700 hover:bg-blue-600
                            hover:shadow-lg"
        />
    </form>
@endsection
