@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Opencast processed events
    </div>

    <div class="flex py-2">
    </div>
    <form action="{{ route('admin.clips.opencast.transfer', $clip) }}"
          method="POST"
          class="w-3/5">
        @csrf
        <div class="mb-6">
            <label class="block mb-2 uppercase pt-4 font-bold text-xs text-gray-700"
                   for="eventID"
            >
                Please select video files
            </label>

            <select class="border border-gray-400 p-2 w-full"
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
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <x-form.button :link="$link=false"
                       type="submit"
                       text="Transfer the selected Event assets"
        />
        <a href="{{$clip->adminPath()}}">
            <span class="py-2 px-8 text-white rounded-md bg-green-700 focus:outline-noe hover:shadow-lg">
                Back to clip
            </span>
        </a>

    </form>
@endsection
