@extends('layouts.backend')

@section('content')
    <div class="flex justify-between border-b border-black text-2xl dark:text-white dark:border-white
                font-normal pb-2">
        <div class="font-semibold">
            {{ __('clip.backend.video files in dropzone') }}
        </div>
    </div>
    <div class="px-2">
        <form action="{{ route('admin.clips.dropzone.transfer', $clip) }}"
              method="POST"
              class="w-3/5 pt-10">
            @csrf
            <label class="mb-2 block text-md font-bold text-gray-700 dark:text-white"
                   for="files[]"
            >
                {{ __('clip.backend.please select one or more audio/video files') }}
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
            </select>
            <div class="pt-20 space-x-4">
                <x-button type="submit" class="bg-blue-600 hover:bg-blue-700">
                    {{ __('clip.backend.actions.add selected audio/video files to clip') }}
                </x-button>
                <x-back-button :url="route('clips.edit',$clip)"
                               class="bg-green-600 hover:bg-green-700">
                    {{ __('common.forms.go back') }}
                </x-back-button>
            </div>
        </form>
    </div>

@endsection
