@use(App\Enums\Acl)
<div class="mt-5 flex flex-col sm:flex-row" x-data="{ selected: {{$defaultChapter}} }"
     x-init="$nextTick(() => { if (selected) { $refs['container' + selected].style.maxHeight = $refs['container' + selected].scrollHeight + 'px'; } })">

    <ul class="shadow-box mb-4 flex flex-col w-full text-center text-lg dark:bg-gray-900 dark:text-white">
        @foreach($chapters as $chapter)
            <li class="relative flex flex-col sm:flex-row w-full rounded-lg pb-5">
                <div class="w-full">
                    <button type="button" class="w-full px-4 sm:px-8 py-4 sm:py-6 text-left border-2 border-gray-600"
                            @click="selected !==  {{ $chapter->id }} ? selected = {{ $chapter->id }} : selected = null">
                        <div class="flex items-center justify-between">
                            <span>
                                {{$chapter->position}} -  {{$chapter->title}}
                            </span>
                            <x-heroicon-o-plus-circle class="h-6 w-6" />
                        </div>
                    </button>
                    <div class="relative max-h-0 overflow-hidden transition-all duration-700"
                         x-ref="container{{$chapter->id}}"
                         x-bind:style="selected == {{ $chapter->id }} ? 'max-height: ' + $refs['container' + {{ $chapter->id }}].scrollHeight + 'px' : ''">
                        <div class="p-4 sm:p-6">
                            <ul class="w-full pt-3">
                                @forelse($chapter->clips()->with('semester')->orderBy('episode')->get() as $clip)
                                    @php
                                        $latestAsset = $clip->latestAsset();
                                    @endphp
                                    <li class="flex flex-col lg:flex-row  lg:space-y-3 content-center
                                    items-center rounded text-center text-sm lg:text-lg sm:text-lg py-4 border-b
                                    border-gray-300 dark:border-gray-700">
                                        <!-- Episode Number -->
                                        <div class="w-1/12 sm:w-full mb-2 sm:mb-0 justify-center">
                                            {{ $clip->episode }}
                                        </div>

                                        <!-- Clip Image -->
                                        <div class="w-2/12 sm:w-full mb-2 sm:mb-0">
                                            <div class="relative h-15 overflow-hidden">
                                                <a href="@if(str_contains(url()->current(), 'admin')){{ route('clips.edit', $clip) }}@else{{ route('frontend.clips.show', $clip) }}@endif">
                                                    <img src="{{ fetchClipPoster($latestAsset?->player_preview) }}"
                                                         alt="preview image"
                                                         class="w-full h-auto max-w-xs sm:max-w-full">
                                                </a>
                                                <div
                                                        class="absolute w-full py-2.5 bottom-0 inset-x-0 bg-blue-600  text-white
                                            text-md text-right pr-2 pb-2 leading-4 ">
                                                    {{ is_null($latestAsset) ? '00:00:00' : gmdate('H:i:s', $latestAsset->duration) }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Clip Title -->
                                        <div class="w-3/12 sm:w-full mb-2 sm:mb-0 text-center  mx-2">
                                            {{ $clip->title }}
                                        </div>
                                        <div class="w-1/12 sm:w-full mb-2 sm:mb-0">
                                            {{ $clip->recording_date->format('Y-m-d') }}
                                        </div>
                                        <div class="w-2/12 sm:w-full mb-2 sm:mb-0">{{ $clip->semester->name }}</div>

                                        <!-- ACL Status and Icons -->
                                        <div class="w-2/12 sm:w-full mb-2 sm:mb-0 flex flex-col sm:flex-row justify-center items-center">
                                            <div class="pr-0 sm:pr-2">
                                                {{ $clip->acls->isEmpty() ? Acl::PUBLIC->lower() : $clip->acls->pluck('name')->implode(',') }}
                                            </div>
                                            @if($clip->acls->doesntContain(Acl::PUBLIC()) && $clip->acls->isNotEmpty())
                                                <div class="flex justify-center items-center mt-2 sm:mt-0">
                                                    @can('watch-video', $clip)
                                                        <x-heroicon-o-lock-open class="h-4 w-4 text-green-500" />
                                                        <span class="sr-only">{{ __('common.unlocked') }} clip</span>
                                                    @else
                                                        <x-heroicon-o-lock-closed class="h-4 w-4 text-red-700" />
                                                        <span class="sr-only">{{ __('common.locked') }} clip</span>
                                                    @endcan
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Play/Edit Button -->
                                        <div class="w-1/12 sm:w-full flex justify-center mb-2 sm:mb-0">
                                            @if($dashboardAction && Request::segment(1) === 'admin')
                                                <a href="{{ route('clips.edit', $clip) }}">
                                                    <x-button class="bg-blue-600 hover:bg-blue-700">
                                                        {{ __('common.actions.edit') }}
                                                    </x-button>
                                                </a>
                                            @else
                                                <a href="{{ route('frontend.clips.show', $clip) }}">
                                                    <button type="submit"
                                                            class="focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-blue-700 hover:bg-blue-500 hover:shadow-lg">
                                                        <x-heroicon-o-play class="h-6 w-6" />
                                                    </button>
                                                </a>
                                            @endif
                                        </div>
                                    </li>

                                @empty
                                    <div class="grid place-items-center w-full">
                                        <div class="mb-4 w-full rounded bg-gray-200 dark:bg-slate-800 p-5 text-center text-sm sm:text-2xl dark:text-white">
                                            {!!  __('series.backend.Series chapter has no clips', ['chapterTitle' =>  $chapter->title]) !!}
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
            <li class="relative flex flex-col sm:flex-row w-full rounded-lg pb-5">
                <div class="w-full">
                    <button type="button" class="w-full px-4 sm:px-8 py-4 sm:py-6 text-left border-2 border-gray-600"
                            @click="selected !== 1 ? selected = 1 : selected = null">
                        <div class="flex items-center justify-between">
                            <span>
                              {{ __('series.common.clips without chapter(s)') }}
                            </span>
                            <x-heroicon-o-plus-circle class="h-6 w-6" />
                        </div>
                    </button>
                    <div class="relative max-h-0 overflow-hidden transition-all duration-700"
                         x-ref="container1"
                         x-bind:style="selected == 1 ? 'max-height: ' + $refs.container1.scrollHeight + 'px' : ''">
                        <div class="p-4 sm:p-6">
                            <ul class="w-full pt-3">
                                @forelse($series->clipsWithoutChapter()->sortBy('episode') as $clip)
                                    <li class="flex flex-col sm:flex-row content-center items-center rounded text-center text-sm sm:text-lg py-2">
                                        <div class="w-full sm:w-1/12 mb-2 sm:mb-0">{{ $clip->episode }}</div>
                                        <div class="w-full sm:w-2/12 mb-2 sm:mb-0">
                                            <div class="mx-auto sm:mx-2 flex h-24 sm:h-full w-full sm:w-48 place-items-center justify-center">
                                                <a href="{{ str_contains(url()->current(), 'admin') ? route('clips.edit', $clip) : route('frontend.clips.show', $clip) }}">
                                                    <img src="{{ fetchClipPoster($clip->latestAsset()?->player_preview) }}"
                                                         alt="preview image" class="w-full h-auto sm:w-auto sm:h-auto">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="w-full sm:w-3/12 mb-2 sm:mb-0 sm:pt-20">{{ $clip->title }}</div>
                                        <div class="w-full sm:w-2/12 mb-2 sm:mb-0 flex justify-center items-center">
                                            <div class="pr-2">
                                                {{ $clip->acls->isEmpty() ? Acl::PUBLIC->lower() : $clip->acls->pluck('name')->implode(',') }}
                                            </div>
                                            @if($clip->acls->doesntContain(Acl::PUBLIC()) && $clip->acls->isNotEmpty())
                                                <div>
                                                    @can('watch-video', $clip)
                                                        <x-heroicon-o-lock-open class="h-4 w-4 text-green-500" />
                                                        <span class="sr-only">{{ __('common.unlocked') }} clip</span>
                                                    @else
                                                        <x-heroicon-o-lock-closed class="h-4 w-4 text-red-700" />
                                                        <span class="sr-only">{{ __('common.locked') }} clip</span>
                                                    @endcan
                                                </div>
                                            @endif
                                        </div>
                                        <div class="w-full sm:w-2/12 mb-2 sm:mb-0">{{ $clip->semester->acronym }}</div>
                                        <div class="w-full sm:w-1/12 mb-2 sm:mb-0">{{ is_null($clip->latestAsset()) ? '00:00:00' : gmdate('H:i:s', $clip->latestAsset()->duration) }}</div>
                                        <div class="w-full sm:w-1/12 mb-2 sm:mb-0">
                                            @if($dashboardAction && Request::segment(1) === 'admin')
                                                <a href="{{ route('clips.edit', $clip) }}">
                                                    <x-button
                                                            class="bg-blue-600 hover:bg-blue-700">{{ __('common.actions.edit') }}</x-button>
                                                </a>
                                            @else
                                                <form method="GET" action="{{ route('clips.edit', $clip) }}">
                                                    <button type="submit"
                                                            class="focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-blue-700 hover:bg-blue-500 hover:shadow-lg">
                                                        <x-heroicon-o-play class="h-6 w-6" />
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </li>
                                @empty
                                    <div class="grid place-items-center w-full">
                                        <div class="mb-4 w-full rounded bg-gray-200 dark:bg-slate-800 p-5 text-center text-sm sm:text-2xl dark:text-white">
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
