@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 font-semibold font-2xl dark:text-white dark:border-white">
        Opencast processed events
    </div>

    <div class="flex py-2">
    </div>
    <form action="{{ route('admin.clips.opencast.transfer', $clip) }}"
          method="POST"
          class="w-3/5">
        @csrf
        <div class="mb-6">
            <label class="mb-2 block pt-4 text-xs font-normal text-gray-700 dark:text-white"
                   for="eventID"
            >
                Please select video files
            </label>

            <select class="w-full border border-gray-400 p-2"
                    type="text"
                    name="eventID"
                    id="listOpencastEvents"
                    required
            >
                @forelse($events as $event)
                    <option value="{{ $event['identifier'] }}">{{ $event['start'].'->'.$event['title'] }}</option>
                @empty
                    <option> no events found for this series</option>

                @endforelse
            </select>
            @error('eventID')
            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <x-form.button :link="$link=false"
                       type="submit"
                       text="Transfer the selected Event assets"
        />
        <a href="{{ route('clips.edit', $clip) }}">
            <span class="rounded-md font-normal bg-green-700 px-8 py-2 text-white hover:shadow-lg focus:outline-noe">
                Back to clip
            </span>
        </a>

    </form>
@endsection
