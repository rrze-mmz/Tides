<div class=" w-full flex bg-white">
    <div class="h-24 w-48 pt-3 ml-2 place-content-center place-items-center justify-center justify-items-center">
        <img src="/images/preview.jpeg" alt="preview image">
    </div>

    <div class=" bg-white p-4 flex flex-col justify-between w-full ">
        <div class="mb-1">
            <div class="text-gray-900 font-bold text-sm"><a href="{{ $clip->path() }}" class="underline">{{ $clip->title }}</a></div>
            <p class="text-gray-700 text-base pt-3">{{ Str::limit($clip->description, 30) }}</p>
        </div>
        <div class="flex items-center">
            <div class="text-sm">
                <p class="text-gray-900 italic">{{ $clip->updated_at }} Presenter</p>
            </div>
        </div>
    </div>
</div>
