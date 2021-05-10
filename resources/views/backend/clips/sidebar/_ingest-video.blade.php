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
            * will start an Opencast workflow and your video will be transcoded directly to Opencast server
        </p>
        <button type="submit"
                class="mt-2 py-2 px-8 text-white bg-green-500 rounded shadow hover:bg-green-600 focus:shadow-outline focus:outline-none"
        >Upload
        </button>
        @error('videoFile')
        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </form>
</div>
