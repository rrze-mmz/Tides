<div class="px-4 py-4 mx-4 w-full h-full bg-white rounded border">
    <header class="items-center pb-2 mb-2 font-semibold text-center border-b"> Upload a Video</header>
    <form action="{{ $clip->adminPath().'/assets' }}"
          enctype="multipart/form-data"
          method="POST"
          class="flex flex-col"
    >
        @csrf
        <input type="file" id="asset" name="asset">
        <div class="flex items-center pt-4 mb-2 align-content-between justify-content-center">
            <legend>Convert to HLS?</legend>
            <input class="mx-auto"
                   type="checkbox"
                   name="should_convert_to_hls"
            >
        </div>

        <x-form.button link="$link=false"
                       type="submit"
                       text="Upload"
                       color="green"
                       additional-classes="w-full"
        />

        @error('asset')
        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </form>
</div>
