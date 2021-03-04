<div class=" w-full flex bg-white">
    <div class="h-24 w-48 pt-3 ml-2 place-content-center place-items-center justify-center justify-items-center">
        <img src="/images/preview.jpeg" alt="preview image">
    </div>

    <div class=" bg-white p-4 flex flex-col justify-between w-full ">
        <div class="mb-1">
            <div class="text-gray-900 font-bold text-sm">
                <a href="@if (str_contains(url()->current(), 'admin')) {{$clip->adminPath().'/edit'}} @else {{ $clip->path() }} @endif" class="underline">
                    {{ $clip->title }}
                </a></div>
            <p class="text-gray-700 text-base py-3">{{ Str::limit($clip->description, 30) }}</p>
        </div>
        <div class="flex items-center">
            <div class="pr-2">
                <svg class="w-3 h-3 " fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
            <div class="text-sm">
                <p class="text-gray-900 italic">{{ $clip->updated_at }} {{ $clip->owner->name }}</p>
            </div>
        </div>
    </div>
</div>
