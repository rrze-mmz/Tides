<div class="flex-row my-10 px-3 py-4 bg-white mt-10 ">
    <div class="font-medium w-full">
        Series chapters
    </div>

    @forelse($series->chapters as $chapter)
        <div class="flex space-x-2 content-center w-full py-4">
            <input type="number" min="0" name="episode" class="flex-none w-20" value="{{$chapter->position}}">
            <input type="text" name="title" class="grow w-1/2" value="{{$chapter->title}}">
            <div class="mt-1">
                <x-form.button :link="route('series.chapters.edit',[$series,$chapter])" type="submit"
                               text="Edit chapter"/>
            </div>
            <div class="mt-1">
                <x-form.button :link="$link=false" color="green" type="submit" text="Set chapter as default"/>
            </div>
            <div class="mt-1">
                <x-form.button :link="$link=false" color="red" type="delete" text="Delete chapter"/>
            </div>
        </div>
    @empty
        <div class="pt-8">
            {{ 'No chapters found for '.$series->title }}
        </div>
    @endforelse
</div>
