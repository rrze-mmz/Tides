<div class="flex my-2 w-full bg-gray-50">
    <div class="flex justify-center justify-items-center  place-items-center mx-2 w-48 h-full">
        <img src="{{ fetchClipPoster($series->clips()->get()->last()?->posterImage) }}" alt="preview image">
    </div>

    <div class="flex flex-col justify-between p-4 w-full bg-gray-50">
        <div class="mb-1">
            <div class="text-sm font-bold text-gray-900">
                <a
                    href="@if (str_contains(url()->current(), 'admin')) {{$series->adminPath()}}
                    @else {{ $series->path() }} @endif"
                    class="underline text-2xl"
                >{{ $series->title }}
                </a>
                <span class="text-sm italic">von {{$series->owner?->getFullNameAttribute()}}</span>
            </div>
            <p class="py-3 text-base text-gray-700">
                {{ (str_contains(url()->current(),'search'))?$series->description:Str::limit($series->description, 30) }}
            </p>
        </div>

        @if($series->presenters()->count() > 0)
            <div class="flex items-center pt-2 justify-content-between">
                <div class="pr-2">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                    >
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M8 13v-1m4 1v-3m4 3V8M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
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
                <svg class="w-4 h-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24"
                     xmlns="http//www.w3.org/2000/svg">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                </svg>
            </div>
            <div class="text-sm">
                <p class="italic text-gray-900">
                    {{ Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $series->updated_at)
                                                            ->format('Y-m-d')  }}
                </p>
            </div>
        </div>

        @if($seriesAcls = $series->fetchClipsAcls())
            <div class="flex items-center pt-2 justify-content-between">
                <div class="pr-2">
                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg"
                    >
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4
                                0 00-8 0v4h8z"
                        >
                        </path>
                    </svg>
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
