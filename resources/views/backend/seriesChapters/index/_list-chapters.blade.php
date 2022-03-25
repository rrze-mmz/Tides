<div class="flex-row my-10 px-3 py-4 bg-white mt-10 ">
    <div class="font-medium w-full">
        Series chapters
    </div>


    @if($series->chapters()->count() > 0)
        <form action="{{route('series.chapters.update',$series)}}"
              method="POST"
        >
            @method('PUT')
            @csrf

            @forelse($series->chapters()->orderBy('position','asc')->get() as $chapter)
                <div class="flex space-x-2 content-center items-center w-full py-4">
                    <input type="number" min="0" name="chapters[{{$chapter->id}}][position]" class="flex-none w-20"
                           value="{{$chapter->position}}">
                    <input type="text" name="chapters[{{$chapter->id}}][title]" class="grow w-1/2"
                           value="{{$chapter->title}}">
                    <div class="mt-1">
                        <x-form.button :link="route('series.chapters.edit',[$series,$chapter])" type="back"
                                       text="Edit chapter"/>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @empty

            @endforelse
            <div class="py-4">
                <x-form.button :link="$link=false"
                               type="submit"
                               text="Update chapters"
                />
                <x-form.button :link="route('series.edit',$series)"
                               color="green"
                               type="back"
                               text="Back to series"

                />
            </div>
        </form>
    @else
        <div class="flex-row">
            <div class="pt-8">
                {{ 'No chapters found for '.$series->title }}
            </div>
            <div class="pt-8">
                <x-form.button :link="route('series.edit',$series)"
                               color="green"
                               type="back"
                               text="Back to series"

                />
            </div>

        </div>

    @endif
</div>
