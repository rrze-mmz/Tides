<div class="flex">
    @if($reorder)
        <form class="w-full" action="{{route('series.clips.reorder', $series)}}" method="POST">
            @csrf
            @endif
            <ul class="pt-3 w-full">
                <li class="flex content-center items-center p-2 mb-2 bg-gray-400 rounded text-center">
                </li>

                @forelse($series->clips->sortBy('episode', SORT_NATURAL) as $clip)
                    <li class="flex content-center items-center p-2 mb-4 text-lg bg-gray-200 rounded text-center">
                        <div class="w-1/12">
                            @if ($reorder)
                                <label>
                                    <input class="w-1/2" type="number" name="episodes[{{$clip->id}}]"
                                           value="{{$clip->episode}}">
                                </label>
                                @error('episodes')
                                <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            @else
                                {{ $clip->episode }}
                            @endif
                        </div>
                        <div class="w-2/12">
                            <div class="flex justify-center justify-items-center place-items-center mx-2 w-48 h-full">
                                <a
                                    href="@if(str_contains(url()->current(), 'admin')) {{$clip->adminPath()}}
                                    @else {{ $clip->path() }}
                                    @endif">
                                    <img src="{{ fetchClipPoster($clip) }}"
                                         alt="preview image"
                                    >
                                </a>
                            </div>
                        </div>
                        <div class="w-3/12"> {{ $clip->title }}</div>
                        <div class="w-2/12 flex justify-center items-center
                        ">
                            <div class="pr-2">
                                {{ ($clip->acls->isEmpty())
                                ?'open'
                                :$clip->acls->except(\App\Enums\Acl::PUBLIC())->pluck('name')->implode(',') }}
                            </div>
                            @if($clip->acls->except(\App\Enums\Acl::PUBLIC())->isEmpty())
                                open
                            @else
                                <div>
                                    @can('watch-video', $clip)
                                        <x-heroicon-o-lock-open class="w-4 h-4 text-green-500"/>
                                        <span class="sr-only">Unlock clip</span>
                                    @else
                                        <x-heroicon-o-lock-closed class="w-4 h-4 text-red-700"/>
                                        <span class="sr-only">Lock clip</span>
                                    @endcan
                                </div>
                            @endif

                            <div class="pl-4">
                            </div>
                        </div>
                        <div class="w-2/12">{{ $clip->semester->name }}</div>
                        <div class="w-1/12"> {{ $clip->assets->first()?->durationToHours()  }}</div>
                        <div class="w-1/12">
                            @if($dashboardAction && Request::segment(1) === 'admin')
                                <a href="{{route('clips.edit', $clip)}}">
                                    <x-button class="bg-blue-600 hover:bg-blue-700">
                                        {{__('common.actions.edit')}}
                                    </x-button>
                                </a>
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
                            {{ __('series.common.no clips') }}
                        </div>
                    </div>
                @endforelse
                @if($reorder)
                    <div class="pt-10">
                        <x-form.button :link="$link=false" type="submit" text="Reorder Series clips"/>
                    </div>
            </ul>
        </form>
    @endif
</div>
