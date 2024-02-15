@php use App\Enums\Acl; @endphp
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
            @php
                $acls = $clip->acls->pluck('id');
            @endphp

            <div class="flex flex-col items-center justify-center space-y-4 py-16">
                {{-- Display all text messages --}}
                @foreach($acls as $acl)
                    @if($acl == Acl::PORTAL())
                        <p class="dark:text-white text-3xl">{{ __('clip.frontend.this clip is exclusively accessible to logged-in users') }}</p>
                    @elseif($acl == Acl::LMS())
                        <p class="dark:text-white text-3xl">{{ __('clip.frontend.access to this clip is restricted to LMS course participants') }}</p>
                    @elseif($acl == Acl::PASSWORD())
                        <p class="dark:text-white text-3xl">{{ __('clip.frontend.this clip requires a password for access') }}</p>
                    @endif
                @endforeach
            </div>

            <div class="w-full flex justify-center items-center space-x-6 px-16">
                {{-- Adjust this section if you expect exactly two buttons. Otherwise, consider a different layout for more buttons. --}}
                @foreach($acls as $acl)
                    @if($acl == Acl::PORTAL())
                        <a href="{{route('login')}}">
                            <x-button class='flex items-center bg-blue-600 hover:bg-blue-700'>
                                Login
                                <x-heroicon-o-arrow-circle-right class="w-6 ml-4" />
                            </x-button>
                        </a>
                    @endif
                    @if($acl == Acl::LMS())
                        <a href="{{$clip->series->lms_link}}">
                            <x-button class='flex items-center bg-blue-600 hover:bg-blue-700'>
                                Go to LMS Course
                                <x-heroicon-o-arrow-circle-right class="w-6 ml-4" />
                            </x-button>
                        </a>
                    @endif
                @endforeach
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
