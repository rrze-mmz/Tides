<div class="flex my-2 w-full bg-white">
    <div class="flex justify-center justify-items-center  place-items-center mx-2 w-48 h-full">
        <img src="{{ fetchClipPoster($clip->get('poster_image')) }}" alt="preview image">
    </div>

    <div class="flex flex-col justify-between p-4 w-full bg-white">
        <div class="mb-1">
            <div class="text-sm font-bold text-gray-900">
                <a href="/clip/{{$clip->get('slug')}}"
                   class="underline"
                >{{ $clip->get('title') }}</a>
            </div>
            <p class="py-3 text-base text-gray-700">
                {{ (str_contains(url()->current(),'search'))?$clip->get('description'):Str::limit($clip->get('description'), 30) }}
            </p>
        </div>
        <div class="flex items-center justify-content-between">
            <div class="pr-2">
                <svg class="w-4 h-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg"
                >
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                </svg>
            </div>
            <div class="text-sm">
                <p class="italic text-gray-900">
                    {{--                    {{ Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $clip->get('updated_at'))->format('Y-m-d')  }}--}}
                </p>
            </div>
        </div>

        <div class="flex items-center pt-2 justify-content-between">
            <div class="pr-2">
                <svg class="w-4 h-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg"
                >
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    >
                    </path>
                </svg>
            </div>
        </div>
        
    </div>
</div>
