<div class="flex-row my-10 px-3 py-2 bg-white mt-10">
    <div class="font-medium w-full">
        New chapter
    </div>

    <form action="{{route('series.chapters.create', $series)}}"
          method="POST"
          class="pt-4"
    >
        @csrf

        <div class="flex space-x-2 content-center w-full">
            <input type="number" min="0" name="position" class="flex-none w-20"
                   value="{{ old('position', $series->chapters()->count()+1) }}">
            <input type="text" name="title" class="grow w-1/2">
            <div class="mt-1">
                <x-form.button :link="$link=false" type="submit" text="Create chapter"/>
            </div>
        </div>
    </form>
    @error('title')
    <div class="col-start-2 col-end-6">
        <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
    </div>
    @enderror
</div>
