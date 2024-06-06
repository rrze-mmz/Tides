<div class="flex flex-row ">
    <div class="w-2/3 pb-6 items-center align-middle mx-auto">
        <div>
            <x-player :clip="$clip" :wowzaStatus="$wowzaStatus"
                      :default-video-url="$defaultVideoUrl" />
        </div>
    </div>
    <div class="w-1/3">
        <div class="flex flex-col space-y-4 dark:text-white w-full justify-items-center">
            @foreach($alternativeVideoUrls as $type=> $url)
                <div class="mx-4">
                    @if($type === 'composite')
                        <a href="{{$url}}"
                           class="video-link flex px-4 py-2 bg-blue-800 border border-transparent rounded-md
                    font-semibold text-xs text-white uppercase tracking-widest
                    hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900
                    focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 items-center"
                           title="composite video stream">
                            <x-heroicon-o-view-columns class="w-6 h-6 fill-white" />
                            <div class="pl-2">
                                Side by Side
                            </div>
                        </a>
                    @endif
                    @if($type === 'presenter')
                        <a href="{{$url}}"
                           class="video-link flex px-4 py-2 bg-blue-800 border border-transparent rounded-md
                    font-semibold text-xs text-white uppercase tracking-widest
                    hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900
                    focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 items-center"
                           title="presenter video stream place-items-center">
                            <x-heroicon-o-user class="w-6 h-6 fill-white" />
                            <div class="pl-2">
                                Camera
                            </div>
                        </a>
                    @endif
                    @if($type === 'presentation')
                        <a href="{{$url}}"
                           class="video-link flex px-4 py-2 bg-blue-800 border border-transparent
                                           rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                           hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900
                                           focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out
                                           duration-150 items-center"
                           title="presentation video stream">
                            <x-heroicon-o-computer-desktop class="w-6 h-6 fill-white" />
                            <div class="pl-2">
                                Slides
                            </div>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="flex flex-col space-y-4 dark:text-white w-full justify-items-center items-center">
            <h4 class="font-bold text-xl py-4">
                Quick Links
            </h4>
            <div class="flex flex-col space-y-4 dark:text-white w-full ">
                <a href="{{ route('frontend.clips.show', $clip) }}" class=" items-center px-4">
                    <x-button type="button"
                              class="bg-green-600 hover:bg-green-700 w-full"
                    >
                        <x-heroicon-o-arrow-right-circle class="w-6 h-6" />
                        <div class="pl-2">
                            Go to public page
                        </div>
                    </x-button>
                </a>
                <a href="{{ route('statistics.clip', $clip) }}" class=" items-center px-4">
                    <x-button type="button"
                              class="bg-green-600 hover:bg-green-700 w-full"
                    >
                        <x-heroicon-o-arrow-right-circle class="w-6 h-6" />
                        <div class="pl-2">
                            Go to statistics page
                        </div>
                    </x-button>
                </a>
            </div>
        </div>
    </div>
</div>
