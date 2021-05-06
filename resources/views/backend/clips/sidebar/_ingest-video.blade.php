<div class="py-4 px-4 mx-4 w-full h-full bg-white rounded border">
    <header class="items-center pb-2 mb-2 font-semibold text-center border-b"> Ingest to Opencast*</header>
    <form action="{{ route('opencast.ingestMediaPackage',$clip) }}"
          enctype="multipart/form-data"
          method="POST"
          class="flex flex-col"
    >
        @csrf
        <input type="file" id="videoFile" name="videoFile">
        <p class="pt-2 text-sm italic">
            * will start an Opencast workflow and you Video will be transcoded directly in Opencast server
        </p>
        <button type="submit"
                class=" mt-3 ml-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-green-500 hover:bg-green-600 hover:shadow-lg"
        >Upload
        </button>
        @error('videoFile')
        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </form>
</div>
