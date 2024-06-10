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
                @if($clip->is_livestream)
                    @if($clip->livestream)
                        <div class="flex flex-col space-y-4 dark:text-white w-full ">
                            <form action="{{route('livestreams.cancelReservation', $clip->livestream->id)}}"
                                  method="POST">
                                @csrf
                                <div class="mx-4 ">
                                    <div class="border-b mb-4">Active livestream room
                                        : {{ $clip->livestream->name }}
                                        <span class="italic text-sm"
                                        >
                                                                        since {{ $clip->livestream->time_availability_start }}
                                                                    </span>
                                    </div>
                                </div>
                                <div class="flex flex-col dark:text-white w-full ">
                                    <div class="items-center px-4">
                                        <x-button type="submit" class="bg-blue-600 hover:bg-blue-700 w-full">
                                            Stop reservation
                                        </x-button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        @can('administrate-portal-pages')
                            <div class="flex flex-col space-y-4 dark:text-white w-full ">
                                <form action="{{ route('livestreams.makeReservation') }}"
                                      method="POST"
                                >
                                    @csrf
                                    <div class="mx-4 items-center ">
                                        <div class="flex flex-col border-b">
                                            <div class="text-lg font-bold">
                                                Livestream room reservation options *
                                            </div>
                                            <span class="py-1 italic text-sm">
                                             *If a room is already reserved is not going to be listed
                                        </span>
                                        </div>
                                        <div>

                                        </div>
                                        <input id="clipID"
                                               type="text"
                                               name="clipID"
                                               value="{{ old('clipID',$clip->id) }}"
                                               class="@error('clipID') is-invalid @enderror hidden">
                                        @error('clipID')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                        <x-form.select2-single field-name="livestreamID"
                                                               label="Room name"
                                                               select-class="select2-tides"
                                                               model="livestream"
                                                               columns="4"
                                                               column-start="2"
                                                               column-end="4"
                                                               :selectedItem="old('livestreamID')"
                                        />
                                    </div>

                                    <div class="flex flex-col dark:text-white w-full ">
                                        <div class="items-center px-4">
                                            <x-button type="submit"
                                                      class="bg-blue-600 hover:bg-blue-700 w-full"
                                            >
                                                <x-heroicon-o-arrow-right-circle class="w-6 h-6" />
                                                <div class="pl-2">
                                                    Livestream reservieren
                                                </div>
                                            </x-button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endcan
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
