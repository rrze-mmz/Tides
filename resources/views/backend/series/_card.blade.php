@if(str_contains(url()->current(), 'admin'))
    @php
        $url = route('series.edit', $series)
    @endphp
@else
    @php
        $url = route('frontend.series.show', $series)
    @endphp
@endif
<div class="relative my-2 bg-gray-50 dark:bg-slate-800 rounded-md dark:border-white font-normal">
    <div class="relative h-15 overflow-hidden">
        <a href="{{ $url }}">
            <img
                src="{{ ($series->lastPublicClip)
                    ? fetchClipPoster($series->lastPublicClip?->latestAsset?->player_preview)
                    : "/images/generic_clip_poster_image.png" }}"
                alt="preview image"
                class="object-cover w-full h-full" />
        </a>
        <div
            class="absolute w-full py-2.5 bottom-0 inset-x-0 bg-blue-600  text-white
                    text-xs text-right pr-2 pb-2 leading-4 ">
            {{ $series->latestClip?->latestAsset?->durationToHours() }}
        </div>
    </div>

    <div class="flex-row justify-between p-2 mb-6 w-full bg-gray-50 dark:bg-slate-800 pb-7">
        <div class="mb-1">
            <div class="text-md font-bold text-gray-900 dark:text-white">
                <a
                    href="{{ $url }}"
                    class="text-lg"
                >
                    {{ $series->title }}
                </a>
            </div>
            <div>
                @if ($series->owner)
                    <span
                        class="text-sm italic dark:text-white">von {{$series->owner->getFullNameAttribute()}}</span>
                @endif
            </div>
            <p class="text-base text-gray-700 dark:text-white">
                {{ strip_tags((str_contains(url()->current(),'search'))
                    ?$series->description
                    : Str::limit($series->description, 30))  }}
            </p>
        </div>

        @if($series->presenters->isNotEmpty())
            <div class="flex items-center pt-2 justify-content-between">
                <div class="pr-2">
                    <x-heroicon-o-user class="h-4 w-4" />
                </div>
                <div class="text-sm">
                    <p class="italic text-gray-900 dark:text-white">
                        {{ $series->presenters
                            ->map(function($presenter){
                                return $presenter->getFullNameAttribute();
                            })->implode(',') }}
                    </p>
                </div>
            </div>
        @endif
        <div class="flex items-center justify-content-between">

            <div class="pr-2">
                <x-heroicon-o-clock class="w-4 h-4 dark:text-white" />
            </div>
            <div class="text-sm">
                <p class="italic text-gray-900 dark:text-white">
                    @if(is_null($series->latestClip))
                        {{ $series->updated_at->diffForHumans() }}
                    @else
                        {{ $series->latestClip->recording_date->diffForHumans()  }}
                    @endif

                </p>
            </div>
        </div>

        @if($seriesAcls = $series->getSeriesACLSUpdated())
            @if($seriesAcls!== 'public')
                <div class="flex items-center justify-content-between">
                    <div class="pr-2">
                        @if($series->checkClipAcls($series->clips))
                            <x-heroicon-o-lock-open class="w-4 h-4 text-green-500 dark:text-white" />
                            <span class="sr-only">Unlock clip</span>
                        @else
                            <x-heroicon-o-lock-closed class="w-4 h-4 text-red-700 dark:text-white" />
                            <span class="sr-only">Lock clip</span>
                        @endif
                    </div>
                    <div class="text-sm">
                        <p class="italic text-gray-900 dark:text-white">
                            {{ $seriesAcls}}
                        </p>
                    </div>
                </div>
            @endif
        @endif

        @if(isset($action) && $action == 'assignClip')
            <form action="{{route('series.clips.assign',['series'=>$series,'clip'=>$clip])}}"
                  method="POST"
                  class="flex flex-col py-2"
            >
                @csrf
                <x-form.button :link="$link=false"
                               type="submit"
                               text="Select this series" />
            </form>
        @endif
    </div>
    @can('edit-series',$series)
        <div class="absolute w-full py-1.5 bottom-0 inset-x-0 text-white dark:text-white
                    text-xs text-right pr-2 pb-2 leading-2 min-pt-5">
            <a href="{{route('series.edit', $series)}}">
                <x-button class="bg-blue-500 hover:bg-blue-700">
                    <div class="flex items-center">
                        <div class="pr-5">
                            {{ __('common.actions.edit') }}
                        </div>
                        <div>
                            <x-heroicon-o-pencil class="w-4 h-4" />
                        </div>
                    </div>

                </x-button>
            </a>
        </div>
    @endcan
</div>
