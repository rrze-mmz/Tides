<div class="flex-row my-10 px-3 py-2 bg-white mt-10 text-lg dark:text-white dark:border-white dark:bg-gray-900">
    <div class="font-normal w-full">
        {{ __('chapter.backend.series chapters') }}
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
                            {{ __('chapter.backend.actions.edit chapter') }}
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
                    {{ __('chapter.backend.actions.update chapters') }}
                </x-button>
                <a href="{{ route('series.edit',$series) }}">
                    <x-button class="bg-green-600 hover:bg-green-700" type="button">
                        {{ __('series.backend.actions.back to edit series') }}
                    </x-button>
                </a>
            </div>
        </form>
    @else
        <div class="flex-row">
            <div class="pt-8 italic">
                {{ __('chapter.backend.no chapters found for', ['seriesTitle' => $series->title]) }}
            </div>
            <div class="pt-8">
                <x-back-button :url="route('series.edit', $series)" class="bg-green-600 hover:bg-green-700">
                    {{ __('series.backend.actions.back to edit series') }}
                </x-back-button>
            </div>

        </div>

    @endif
</div>
