<div class="w-full py-4 px-4 mx-4 h-full bg-white rounded border">
    <h2 class="text-xl font-normal py-4 -ml-5 mb-3 border-l-4 border-blue-600 pl-4 ">
        Upload a Video
    </h2>
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
