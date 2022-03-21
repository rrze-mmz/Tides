<div class="flex my-4">
    Add clips to chapter: {{$chapter->title}}
</div>
<form action="{{ route('series.chapters.update',[$series,$chapter]) }}"
      method="POST"
      class="flex"
>
    @method('PATCH')
    @csrf
    <div class=" flex-row w-full">
        <div class="w-full">
            <select name="ids[]"
                    class="select2-tides px-4 py-4 h-4 w-full focus:outline-none focus:bg-white focus:border-blue-500"
                    multiple style="width:100%">
                @foreach ($series->clipsWithoutChapter($chapter) as $clip)
                    <option value="{{$clip->id}}"> {{$clip->title}}</option>
                @endforeach

            </select>
            @error('ids')
            <div class="col-start-2 col-end-6">
                <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
            </div>
            @enderror
        </div>
        <div class="pt-8">
            <x-form.button :link="$link=false" type="submit" text="Add selected clips to chapter"/>
        </div>
    </div>

</form>
