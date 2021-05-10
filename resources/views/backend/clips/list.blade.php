
<div class="flex pt-8 pb-2 text-lg font-semibold border-b border-black">
    Clips
</div>
<div class="flex">
    <ul class="pt-3 w-full">
        <li class="flex content-center items-center p-5 mb-4 text-lg bg-gray-400 rounded">
            <div class="pb-2 w-1/5 border-b border-black">Poster</div>
            <div class="pb-2 w-1/5 border-b border-black">Episode</div>
            <div class="pb-2 w-3/5 border-b border-black">Title</div>
            <div class="pb-2 w-1/5 border-b border-black">Duration</div>
            <div class="pb-2 w-1/5 border-b border-black">Actions</div>
        </li>

        @forelse($series->clips->sortBy('episode', SORT_NATURAL) as $clip)
            <li class="flex content-center items-center p-5 mb-4 text-lg bg-gray-200 rounded">
                <div class="w-1/5">
                    <div class="flex justify-center justify-items-center place-items-center mx-2 w-48 h-full">
                        <a href="{{$clip->adminPath()}}"><img
                                src="{{ fetchClipPoster($clip->posterImage) }}" alt="preview image">
                        </a>
                    </div>
                </div>
                <div class="w-1/5"> {{ $clip->episode }}</div>
                <div class="w-3/5"> {{ $clip->title }}</div>
                <div class="w-1/5"> {{ $clip->assets()->first()?->durationToHours()  }}</div>
                <div class="w-1/5">
                    @if(str_contains(url()->current(), 'admin'))
                    <form method="GET"
                          action="{{$clip->adminPath() }}"
                    >
                        <button type="submit"
                                class="py-2 px-8 text-white bg-blue-500 rounded shadow hover:bg-blue-600 focus:shadow-outline focus:outline-none">
                            Edit
                        </button>
                    </form>
                    @else
                        <form method="GET"
                              action="{{$clip->Path() }}"
                        >
                            <button type="submit"
                                    class="focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-blue-700 hover:bg-blue-500 hover:shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
            </li>
        @empty
            No clips
        @endforelse
    </ul>
</div>
