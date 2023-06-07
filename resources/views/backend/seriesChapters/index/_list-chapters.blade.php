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
                    <a href="{{ route('series.chapters.edit',[$series,$chapter]) }}">
                        <x-button class="bg-blue-600 hover:bg-blue-700 " type="button">
                            Edit chapter
                        </x-button>
                    </a>
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
                <x-button class="bg-blue-600 hover:bg-blue-700" type="submit">
                    Update chapters
                </x-button>
                <a href="{{ route('series.edit',$series) }}">
                    <x-button class="bg-green-600 hover:bg-green-700" type="button">
                        Back to series
                    </x-button>
                </a>
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
