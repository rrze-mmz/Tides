@use(App\Enums\Acl)
@if(method_exists($series, 'chapters') && $series->chapters()->count() > 0)
    @php
        $defaultChapter = ($series->chapters->filter(function($chapter){ return $chapter->default;})->first()?->id)??'0';
    @endphp
    <div class="mt-5 flex" x-data="{ selected: {{$defaultChapter}} }"
         x-init="$nextTick(() => { if (selected) { $refs['container' + selected].style.maxHeight = $refs['container' + selected].scrollHeight + 'px'; } })">

        <ul class="shadow-box mb-4 flex flex-col w-full text-center text-lg dark:bg-gray-900 dark:text-white">
            @foreach($chapters as $chapter)
                <li class="relative flex w-full rounded-lg pb-5">
                    <div class="w-full">
                        <button type="button" class="w-full px-8 py-6 text-left  border-2 border-gray-600 py-4"
                                @click="selected !==  {{ $chapter->id }}? selected = {{ $chapter->id }} : selected = null">
                            <div class="flex items-center justify-between">
                                <span>
                                   {{$chapter->position}} -  {{$chapter->title}}
                                </span>
                                <x-heroicon-o-plus-circle class="h-6 w-6" />
                            </div>
                        </button>
                        <div class="relative max-h-0 overflow-hidden transition-all duration-700"
                             x-ref="container{{$chapter->id}}"
                             x-bind:style="selected == {{ $chapter->id }} ? 'max-height: ' + $refs['container' + {{ $chapter->id }}].scrollHeight + 'px' : ''"
                        >
                            <div class="p-6">
                                <ul class="w-full pt-3">
                                    @forelse($chapter->clips->sortBy('episode') as $clip)
                                        <li class="flex content-center items-center rounded  text-center text-lg py-2">
                                            <div class="w-1/12">
                                                {{ $clip->episode }}
                                            </div>
                                            <div class="w-2/12">
                                                <div
                                                        class="mx-2 flex h-full w-48 place-items-center justify-center
                                                    justify-items-center"
                                                >
                                                    <a href="@if(str_contains(url()->current(), 'admin')){{ route('clips.edit', $clip) }}@else{{  route('frontend.clips.show', $clip)}}@endif">
                                                        <img
                                                                src="{{ fetchClipPoster($clip->latestAsset()?->player_preview) }}"
                                                                alt="preview image"
                                                        >
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="w-3/12"> {{ $clip->title }}</div>
                                            <div class="w-2/12 flex justify-center items-center">
                                                <div class="pr-2">
                                                    {{ ($clip->acls->isEmpty())
                                                      ? Acl::PUBLIC->lower()
                                                      :$clip->acls->pluck('name')->implode(',')
                                                    }}
                                                </div>
                                                @if($clip->acls->doesntContain(Acl::PUBLIC()) && $clip->acls->isNotEmpty())
                                                    <div>
                                                        @can('watch-video', $clip)
                                                            <x-heroicon-o-lock-open class="h-4 w-4 text-green-500" />
                                                            <span class="sr-only">
                                                                {{ __('common.unlocked') }} clip
                                                            </span>
                                                        @else
                                                            <x-heroicon-o-lock-closed class="h-4 w-4 text-red-700" />
                                                            <span class="sr-only">
                                                                {{ __('common.locked') }} clip
                                                            </span>
                                                        @endcan
                                                    </div>
                                                @endif

                                                <div class="pl-4">
                                                </div>
                                            </div>
                                            <div class="w-2/12">{{ $clip->semester->acronym }}</div>
                                            <div class="w-1/12">
                                                {{
                                                (is_null($clip->latestAsset())?'00:00:00':gmdate('H:i:s', $clip->latestAsset()->duration))
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
                                                          action="{{ route('clips.edit', $clip)  }}"
                                                    >
                                                        <button type="submit"
                                                                class="focus:outline-none text-white text-sm py-1.5
                                                                px-5 rounded-md bg-blue-700 hover:bg-blue-500
                                                                hover:shadow-lg"
                                                        >
                                                            <x-heroicon-o-play class="h-6 w-6" />
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </li>
                                    @empty
                                        <div class="grid place-items-center">
                                            <div class="mb-4 w-full rounded bg-gray-200 p-5 text-center text-2xl">
                                                {!!  __('series.backend.Series chapter has no clips', [
                                                'chapterTitle' =>  $chapter->title
                                                ])  !!}
                                            </div>
                                        </div>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
            @if($series->clipsWithoutChapter()->isNotEmpty())
                <li class="relative flex w-full rounded-lg pb-5">
                    <div class="w-full">
                        <button type="button" class="w-full px-8 py-6 text-left  border-2 border-gray-600"
                                @click="selected !==  1 ? selected = 1  : selected = null">
                            <div class="flex items-center justify-between">
                                <span>
                                  {{ __('series.common.clips without chapter(s)') }}
                                </span>
                                <x-heroicon-o-plus-circle class="h-6 w-6" />
                            </div>
                        </button>
                        <div class="relative max-h-0 overflow-hidden transition-all duration-700"
                             x-ref="container1"
                             x-bind:style="selected == 1? 'max-height: ' + $refs.container1.scrollHeight + 'px' : ''">
                            <div class="p-6">
                                <ul class="w-full pt-3">
                                    @forelse($series->clipsWithoutChapter()->sortBy('episode') as $clip)
                                        <li class="flex content-center items-center rounded  text-center text-lg py-2">
                                            <div class="w-1/12">
                                                {{ $clip->episode }}
                                            </div>
                                            <div class="w-2/12">
                                                <div
                                                        class="mx-2 flex h-full w-48 place-items-center
                                                        justify-center justify-items-center"
                                                >
                                                    <a
                                                            href="
                                                        @if(str_contains(url()->current(), 'admin'))
                                                            {{ route('clips.edit', $clip) }}
                                                        @else
                                                            {{ route('frontend.clips.show', $clip) }}
                                                        @endif
                                    ">
                                                        <img
                                                                src="{{ fetchClipPoster($clip->latestAsset()?->player_preview) }}"
                                                                alt="preview image"
                                                        >
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="w-3/12"> {{ $clip->title }}</div>
                                            <div class="w-2/12 flex justify-center items-center">
                                                <div class="pr-2">
                                                    {{ ($clip->acls->isEmpty())
                                                      ? Acl::PUBLIC->lower()
                                                      :$clip->acls->pluck('name')->implode(',')
                                                    }}
                                                </div>
                                                @if($clip->acls->doesntContain(Acl::PUBLIC()) && $clip->acls->isNotEmpty())
                                                    <div>
                                                        @can('watch-video', $clip)
                                                            <x-heroicon-o-lock-open class="h-4 w-4 text-green-500" />
                                                            <span class="sr-only">
                                                                {{ __('common.unlocked') }} clip
                                                            </span>
                                                        @else
                                                            <x-heroicon-o-lock-closed class="h-4 w-4 text-red-700" />
                                                            <span class="sr-only">
                                                                {{ __('common.locked') }} clip
                                                            </span>
                                                        @endcan
                                                    </div>
                                                @endif
                                                <div class="pl-4">
                                                </div>
                                            </div>
                                            <div class="w-2/12">{{ $clip->semester->acronym }}</div>
                                            <div class="w-1/12">
                                                {{
                                                (is_null($clip->latestAsset())?'00:00:00':gmdate('H:i:s', $clip->latestAsset()->duration))
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
                                                          action="{{ route('clips.edit', $clip)  }}"
                                                    >
                                                        <button type="submit"
                                                                class="focus:outline-none text-white text-sm py-1.5
                                                                px-5 rounded-md bg-blue-700 hover:bg-blue-500
                                                                hover:shadow-lg"
                                                        >
                                                            <x-heroicon-o-play class="h-6 w-6" />
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </li>

                                    @empty
                                        <div class="grid place-items-center">
                                            <div class="mb-4 w-full rounded bg-gray-200 dark:bg-slate-800 p-5
                                            text-center text-2xl dark:text-white">>
                                                {{ __('series.common.no clips') }}
                                            </div>
                                        </div>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                </li>
            @endif
        </ul>
    </div>
@else
    <div class="mt-5 flex">
        @if($reorder)
            <form class="w-full" action="{{route('series.clips.reorder', $series)}}" method="POST">
                @csrf
                @endif
                <ul class="w-full pt-3">
                    @forelse($clips as $clip)
                        <li class="mb-4 flex content-center items-center rounded
                        @if($clip->is_public) bg-gray-300 dark:bg-gray-700  @else bg-gray-500 dark:bg-blue-700  @endif
                         p-2 text-center text-lg  dark:text-white">
                            <div class="w-1/12">
                                @if ($reorder)
                                    <label>
                                        <input class="w-1/2 dark:text-black" type="number"
                                               name="episodes[{{$clip->id}}]"
                                               value="{{$loop->index + 1}}"
                                        >
                                        <div class="col-start-2 col-end-6">
                                            <p class="mt-2 w-full text-sm text-green-500 dark:text-green-200">
                                                {{ __('series.backend.actual episode') }} {{ $clip->episode }}
                                            </p>
                                        </div>
                                    </label>
                                    @error('episodes')
                                    <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                @else
                                    {{ $clip->episode }}
                                @endif
                            </div>
                            <div class="w-2/12">
                                <div class="mx-2 flex h-full w-48 place-items-center justify-center
                                justify-items-center"
                                >
                                    <a href="@if(str_contains(url()->current(), 'admin')){{ route('clips.edit', $clip) }}
                                    @else{{ route('frontend.clips.show', $clip) }}@endif"
                                    >
                                        <img src="{{ fetchClipPoster($clip->latestAsset()?->player_preview) }}"
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
                                      ? Acl::PUBLIC->lower()
                                      :$clip->acls->pluck('name')->implode(',')
                                    }}
                                </div>
                                @if($clip->acls->doesntContain(Acl::PUBLIC()) && $clip->acls->isNotEmpty())
                                    <div>
                                        @can('watch-video', $clip)
                                            <x-heroicon-o-lock-open class="h-4 w-4 text-green-500" />
                                            <span class="sr-only">
                                                {{ __('common.unlocked') }} clip
                                            </span>
                                        @else
                                            <x-heroicon-o-lock-closed class="h-4 w-4 text-red-700" />
                                            <span class="sr-only">
                                                {{ __('common.locked') }} clip
                                            </span>
                                        @endcan
                                    </div>
                                @endif

                                <div class="pl-4">
                                </div>
                            </div>
                            <div class="w-2/12">{{ $clip->semester }}</div>
                            <div class="w-1/12">
                                {{
                                (is_null($clip->latestAsset())?'00:00:00':gmdate('H:i:s', $clip->latestAsset()->duration))
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
                                          action="{{ route('frontend.clips.show', $clip) }}"
                                    >
                                        <button type="submit"
                                                class="focus:outline-none text-white text-sm py-1.5 px-5 rounded-md
                                                bg-blue-700 dark:bg-white hover:bg-blue-500 dark:hover:bg-gray-600
                                                hover:shadow-lg"
                                        >
                                            <x-heroicon-o-play class="h-6 w-6 dark:text-gray-900" />
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </li>

                    @empty
                        <div class="grid place-items-center">
                            <div
                                    class="mb-4 w-full rounded bg-gray-200 dark:bg-slate-800 p-5 text-center text-2xl dark:text-white">
                                {{ __('series.common.no clips') }}
                            </div>
                        </div>
                    @endforelse
                    @if($reorder)
                        <div class="pt-10 space-x-4">
                            <x-button class="bg-blue-600 hover:bg-blue-700">
                                {{ __('series.backend.actions.reorder series clips') }}
                            </x-button>
                            <x-back-button :url="route('series.edit',$series)"
                                           class="bg-green-600 hover:bg-green-700">
                                {{ __('common.forms.go back') }}
                            </x-back-button>
                        </div>
                </ul>
            </form>
        @endif
    </div>
@endif

