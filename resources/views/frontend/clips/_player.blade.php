@use(App\Models\Series)

<div class="flex flex-col">

    <div class="flex flex-col content-center justify-center pt-6">
        @if($clip->checkAcls())
            <div>
                <x-player :clip="$clip" :wowzaStatus="$wowzaStatus" :default-video-url="$defaultVideoUrl" />
            </div>
            @if ($alternativeVideoUrls->count() > 1)
                <div class="pb-5">
                    <div class="flex space-x-4 pt-10 dark:text-white">
                        @foreach($alternativeVideoUrls as $type=> $url)
                            <div>
                                @if($type === 'presenter')
                                    <a href="{{$url}}"
                                       class="video-link flex px-4 py-2 bg-blue-800 border border-transparent rounded-md
                    font-semibold text-xs text-white uppercase tracking-widest
                    hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900
                    focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
                                       title="presenter video stream">
                                        <x-heroicon-o-user class="w-6 h-6 fill-white" />
                                    </a>
                                @endif
                                @if($type === 'presentation')
                                    <a href="{{$url}}"
                                       class="video-link flex px-4 py-2 bg-blue-800 border border-transparent rounded-md
                    font-semibold text-xs text-white uppercase tracking-widest
                    hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900
                    focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
                                       title="presentation video stream">
                                        <x-heroicon-o-desktop-computer class="w-6 h-6 fill-white" />
                                    </a>
                                @endif
                                @if($type === 'composite')
                                    <a href="{{$url}}"
                                       class="video-link flex px-4 py-2 bg-blue-800 border border-transparent rounded-md
                    font-semibold text-xs text-white uppercase tracking-widest
                    hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900
                    focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
                                       title="composite video stream">
                                        <x-heroicon-o-view-grid class="w-6 h-6 fill-white" />
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <div class="flex content-center justify-center">
                <p class="dark:text-white text-3xl py-16">{{ __('clip.frontend.not authorized to view video') }}</p>
            </div>
        @endif
    </div>


    <div class="flex">
        <div x-data="{ open: false }">
            <div class="flex w-full pt-4 pr-4">
                <a href="#courseFeeds"
                   x-on:click="open = ! open"
                   class="flex px-4 py-2 bg-blue-800 border border-transparent rounded-md
                    font-semibold text-xs text-white uppercase tracking-widest
                    hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900
                    focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Feeds
                    <x-heroicon-o-rss class="ml-4 h-4 w-4 fill-white" />
                </a>
            </div>
            <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-0"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-10"
                 x-transition:leave-end="opacity-0 translate-y-0" class="w-full p-4">
                <ul>
                    @foreach($assetsResolutions as $key=>$resolutionText)
                        <li>
                            <a href="{{route('frontend.clips.feed', [$clip, $resolutionText])}}"
                               class="underline dark:text-white">
                                {{ $resolutionText }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="flex justify-around border-b-2 border-gray-500 pt-8 pb-3">

        @if ($clip->series_id)
            <div class="flex items-center dark:text-white">
                <x-heroicon-o-academic-cap class="h-6 w-6" />
                <a href="{{ route('frontend.series.show', $clip->series) }}">
                    <span class="pl-3 underline"> {{ $clip->series->title }}</span>
                </a>

            </div>
        @endif
        <div class="flex items-center dark:text-white">
            <x-heroicon-o-user-group class="h-6 w-6" />
            <span class="pl-3"> {{ $clip->presenters->pluck(['full_name'])->implode(', ') }} </span>
        </div>

        @if($clip->is_livestream)
            <div class="flex items-center dark:text-white">
                <x-heroicon-o-clock class="h-6 w-6" />
                <span class="pl-3"></span>
                LIVESTREAM
            </div>
        @else
            <div class="flex items-center dark:text-white">
                <x-heroicon-o-clock class="h-6 w-6" />
                <span class="pl-3"></span> {{ $clip->assets()->first()->durationToHours() }} Min
            </div>
        @endif


        <div class="flex items-center dark:text-white">
            <x-heroicon-o-calendar class="h-6 w-6" />
            <span class="pl-3">{{ $clip->created_at->format('Y-m-d') }}</span>
        </div>

        <div class="flex items-center dark:text-white">
            <x-heroicon-o-upload class="h-6 w-6" />
            <span class="pl-3"> {{ $clip->assets->first()?->updated_at }}</span>
        </div>

        <div class="flex items-center dark:text-white">
            <x-heroicon-o-eye class="h-6 w-6" />
            <span class="pl-3"> 0 Views </span>
        </div>

    </div>
</div>
