<div class="mx-4 h-full w-full rounded border bg-white px-4 py-4">
    <h2 class="mb-3 -ml-5 border-l-4 border-blue-600 py-4 pl-4 text-xl font-normal">
        Upload a Video
    </h2>
    <form action="{{ route('admin.clips.asset.transferSingle',$clip) }}"
          enctype="multipart/form-data"
          method="POST"
          class="flex flex-col"
    >
        @csrf
        <input type="file" id="asset" name="asset">

        <x-form.button link="$link=false"
                       type="submit"
                       text="Upload"
                       color="green"
                       additional-classes="w-full mt-6"
        />

        @error('asset')
        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </form>
</div>
