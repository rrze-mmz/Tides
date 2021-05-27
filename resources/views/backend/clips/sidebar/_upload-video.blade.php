<div class="w-full py-4 px-4 mx-4 h-full bg-white rounded border">
    <header class="items-center pb-2 mb-2 font-semibold text-center border-b"> Upload a Video </header>
    <form action="{{ $clip->adminPath().'/assets' }}"
          enctype="multipart/form-data"
          method="POST"
          class="flex flex-col"
    >
        @csrf
        <input type="file" id="asset" name="asset">
        <div class="flex pt-4 align-content-between items-center justify-content-center">
            <legend>Convert to HLS?</legend>
            <input class="mx-auto"
                   type="checkbox"
                   name="should_convert_to_hls"
            >
        </div>

        <button type="submit"
                class=" mt-3 ml-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-green-500
                        hover:bg-green-600 hover:shadow-lg"
        >Upload</button>
        @error('asset')
        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </form>
</div>
