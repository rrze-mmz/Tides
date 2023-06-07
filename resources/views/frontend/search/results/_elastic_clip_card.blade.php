<div class="my-2 flex w-full bg-white">
    <div class="mx-2 flex h-full w-48 place-items-center justify-center justify-items-center">
        <img src="{{ 'thumbnails/'.$clip->get('posterImage') }}"
             alt="preview image">
    </div>

    <div class="flex w-full flex-col justify-between bg-white p-4">
        <div class="mb-1">
            <div class="text-sm font-bold text-gray-900">
                <a href="/clips/{{$clip->get('slug')}}"
                   class="underline"
                >{{ $clip->get('title') }}</a>
            </div>
            <p class="py-3 text-base text-gray-700">
                {{ (str_contains(url()->current(),'search'))?$clip->get('description'):Str::limit($clip->get('description'), 30) }}
            </p>
        </div>
        <div class="flex items-center justify-content-between">
            <div class="pr-2">
                <svg class="h-4 w-4"
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
                <svg class="h-4 w-4"
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
