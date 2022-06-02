<div class="flex-row my-2 bg-gray-50">
    <div class="relative  h-30 overflow-hidden">
        <img src="{{ fetchClipPoster($series->clips()->get()->last()?->posterImage) }}" alt="preview image"
             class="object-cover w-full h-full"/>
        <div
            class="absolute w-full py-2.5 bottom-0 inset-x-0 bg-blue-400 text-white
                    text-xs text-right pr-2 pb-2 leading-4">
            {{ $series->latestClip?->assets->first()?->durationToHours() }}
        </div>
    </div>

    <div class="flex-row justify-between p-2 w-full bg-gray-50">
        <div class="mb-1">
            <div class="text-md font-bold text-gray-900">
                <a
                    hover:
                    href="@if (str_contains(url()->current(), 'admin')) {{$series->adminPath()}}
                    @else {{ $series->path() }} @endif"
                    class="underline text-md"
                >
                    {{ (request()->routeIs('series.index') || request()->routeIs('frontend.series.index'))
                        ? $series->title
                        :  $series->title}}
                </a>

            </div>
            <div>
                <span class=" text-sm italic">von {{$series->owner?->getFullNameAttribute()}}</span>
            </div>
            <p class="text-base text-gray-700">
                {{ strip_tags((str_contains(url()->current(),'search'))?$series->description : Str::limit($series->description, 30))  }}
            </p>
        </div>

        @if($series->presenters()->count() > 0)
            <div class="flex items-center pt-2 justify-content-between">
                <div class="pr-2">
                    <x-heroicon-o-user class="h-4 w-4"/>
                </div>
                <div class="text-sm">
                    <p class="italic text-gray-900">
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
                <x-heroicon-o-clock class="w-4 h-4"/>
            </div>
            <div class="text-sm">
                <p class="italic text-gray-900">
                    {{ Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $series->updated_at)
                                                            ->format('Y-m-d')  }}
                </p>
            </div>
        </div>

        @if($seriesAcls = $series->fetchClipsAcls())
            <div class="flex items-center justify-content-between">
                <div class="pr-2">
                    <x-heroicon-o-lock-closed class="w-4 h-4"/>
                </div>
                <div class="text-sm">
                    <p class="italic text-gray-900">
                        {{ $seriesAcls }}
                    </p>
                </div>
            </div>
        @endif

        @if(isset($action) && $action == 'assignClip')
            <form action="{{route('series.clips.assign',['series'=>$series,'clip'=>$clip])}}"
                  method="POST"
                  class="flex flex-col py-2"
            >
                @csrf
                <x-form.button :link="$link=false"
                               type="submit"
                               text="Select this series"/>
            </form>
        @endif
    </div>
</div>
