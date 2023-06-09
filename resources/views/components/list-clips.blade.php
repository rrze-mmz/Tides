@if($series->chapters()->count() > 0)
    <div class="mt-5 flex border-t-1 border-t" x-data="{selected: 0 }">
        <ul class="shadow-box mb-4 flex flex-col w-full   text-center text-lg">
            @foreach($series->chapters()->orderBy('position')->get() as $chapter)
                <li class="relative flex w-full rounded-lg pb-5">
                    <div class="w-full">
                        <button type="button" class="w-full px-8 py-6 text-left  border-2 border-gray-600 py-4"
                                @click="selected !==  {{ $chapter->id }}? selected = {{ $chapter->id }} : selected = null">
                            <div class="flex items-center justify-between">
                                <span>
                                   {{$chapter->position}} -  {{$chapter->title}}
                                </span>
                                <x-heroicon-o-plus-circle class="h-6 w-6"/>
                            </div>
                        </button>
                        <div class="relative max-h-0 overflow-hidden transition-all duration-700"
                             x-ref="container{{$chapter->id}}"
                             x-bind:style="selected == {{ $chapter->id }}? 'max-height: ' + $refs.container{{$chapter->id}}.scrollHeight + 'px' : ''">
                            <div class="p-6">
                                <ul class="w-full pt-3">
                                    @forelse($chapter->clips as $clip)
                                        <li class="flex content-center items-center rounded  text-center text-lg py-2">
                                            <div class="w-1/12">
                                                {{ $clip->episode }}
                                            </div>
                                            <div class="w-2/12">
                                                <div
                                                    class="mx-2 flex h-full w-48 place-items-center justify-center justify-items-center">
                                                    <a
                                                        href="@if(str_contains(url()->current(), 'admin')) {{$clip->adminPath()}}
                                    @else {{ $clip->path() }}
                                    @endif">
                                                        <img
                                                            src="{{ fetchClipPoster($clip->latestAsset?->player_preview) }}"
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
                                                            <x-heroicon-o-lock-open class="h-4 w-4 text-green-500"/>
                                                            <span class="sr-only">Unlock clip</span>
                                                        @else
                                                            <x-heroicon-o-lock-closed class="h-4 w-4 text-red-700"/>
                                                            <span class="sr-only">Lock clip</span>
                                                        @endcan
                                                    </div>
                                                @endif

                                                <div class="pl-4">
                                                </div>
                                            </div>
                                            <div class="w-2/12">{{ $clip->semester->acronym }}</div>
                                            <div class="w-1/12">
                                                {{
                                                (is_null($clip->latestAsset)?'00:00:00':gmdate('H:i:s', $clip->latestAsset->duration))
                                                }}
                                            </div>
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
                                                            <x-heroicon-o-play class="h-6 w-6"/>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </li>

                                    @empty
                                        <div class="grid place-items-center">
                                            <div class="mb-4 w-full rounded bg-gray-200 p-5 text-center text-2xl">
                                                {{ __('series.common.no clips') }}
                                            </div>
                                        </div>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                </li>
            @endforeach
        </ul>
    </div>
@else
    <div class="mt-5 flex border-t-2 border-t">
        @if($reorder)
            <form class="w-full" action="{{route('series.clips.reorder', $series)}}" method="POST">
                @csrf
                @endif
                <ul class="w-full pt-3">

                    @forelse($clips as $clip)
                        <li class="mb-4 flex content-center items-center rounded bg-gray-200 p-2 text-center text-lg">
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
                                <div
                                    class="mx-2 flex h-full w-48 place-items-center justify-center justify-items-center">
                                    <a
                                        href="@if(str_contains(url()->current(), 'admin')) {{$clip->adminPath()}}
                                    @else {{ $clip->path() }}
                                    @endif">
                                        <img
                                            src="{{ fetchClipPoster($clip->latestAsset?->player_preview) }}"
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
                                            <x-heroicon-o-lock-open class="h-4 w-4 text-green-500"/>
                                            <span class="sr-only">Unlock clip</span>
                                        @else
                                            <x-heroicon-o-lock-closed class="h-4 w-4 text-red-700"/>
                                            <span class="sr-only">Lock clip</span>
                                        @endcan
                                    </div>
                                @endif

                                <div class="pl-4">
                                </div>
                            </div>
                            <div class="w-2/12">{{ $clip->semester }}</div>
                            <div class="w-1/12">
                                {{
                                (is_null($clip->latestAsset)?'00:00:00':gmdate('H:i:s', $clip->latestAsset->duration))
                                }}
                            </div>
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
                                            <x-heroicon-o-play class="h-6 w-6"/>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </li>

                    @empty
                        <div class="grid place-items-center">
                            <div class="mb-4 w-full rounded bg-gray-200 p-5 text-center text-2xl">
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
@endif

