<div class="flex">
    @if($reorder)
        <form class="w-full" action="{{route('series.clips.reorder', $series)}}" method="POST">
            @csrf
            @endif
            <ul class="pt-3 w-full">
                <li class="flex content-center items-center p-5 mb-4 text-lg bg-gray-400 rounded text-center">
                    <div class="pb-2 w-1/12 border-b border-black">Episode</div>
                    <div class="pb-2 w-2/12 border-b border-black">Poster</div>
                    <div class="pb-2 w-3/12 border-b border-black">Title</div>
                    <div class="pb-2 w-2/12 border-b border-black">Access via</div>
                    <div class="pb-2 w-2/12 border-b border-black">Semester</div>
                    <div class="pb-2 w-1/12 border-b border-black">Duration</div>
                    <div class="pb-2 w-1/12 border-b border-black">Actions</div>
                </li>

                @forelse($series->clips->sortBy('episode', SORT_NATURAL) as $clip)
                    <li class="flex content-center items-center p-5 mb-4 text-lg bg-gray-200 rounded text-center">
                        <div class="w-1/12">
                            @if ($reorder)
                                <input class="w-1/2" type="number" name="episodes[{{$clip->id}}]"
                                       value="{{$clip->episode}}">
                            @else
                                {{ $clip->episode }}
                            @endif
                        </div>
                        <div class="w-2/12">
                            <div class="flex justify-center justify-items-center place-items-center mx-2 w-48 h-full">
                                <a href="{{$clip->adminPath()}}"><img
                                        src="{{ fetchClipPoster($clip->posterImage) }}" alt="preview image">
                                </a>
                            </div>
                        </div>
                        <div class="w-3/12"> {{ $clip->title }}</div>
                        <div
                            class="w-2/12">{{ ($clip->acls->isEmpty())?'open':$clip->acls()->pluck('name')->implode(',') }}</div>
                        <div class="w-2/12">{{ $clip->semester->name }}</div>
                        <div class="w-1/12"> {{ $clip->assets->first()?->durationToHours()  }}</div>
                        <div class="w-1/12">
                            @if($dashboardAction && Request::segment(1) === 'admin')
                                <x-form.button :link="$clip->adminPath()" type="submit" text="Edit"/>
                            @else
                                <form method="GET"
                                      action="{{$clip->Path() }}"
                                >
                                    <button type="submit"
                                            class="focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-blue-700
                                            hover:bg-blue-500 hover:shadow-lg"
                                    >
                                        <x-heroicon-o-play class="w-6 h-6"/>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </li>

                @empty
                    <div class="grid place-items-center">
                        <div class=" w-full p-5 mb-4 text-2xl bg-gray-200 rounded text-center">
                            No clips
                        </div>
                    </div>
                @endforelse
                @if($reorder)
                    <div class="pt-10">
                        <x-form.button :link="$link=false" type="submit" text="Reorder Series clips"/>
                    </div>
        </form>
        @endif
        </ul>
</div>
