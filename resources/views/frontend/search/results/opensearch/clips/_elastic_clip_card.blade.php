@use(Illuminate\Support\Carbon)
<figure class="my-2 flex w-full align-middle bg-indigo-200 rounded-2xl">
    <div class="flex w-full flex-col justify-between p-4">
        <div class="mb-1">
            <div class="text-lg font-bold text-gray-900">
                <a href="{{ route('frontend.clips.show', $clip['slug']) }}"
                   class=""
                >
                    {{ $clip['title'].' |'.$clip['semester'].' |  ClipID:'.$clip['id'] }}
                </a>
            </div>
            <p class="py-3 text-base text-gray-700">
                {!! $clip['description'] !!}

            </p>
        </div>
        <div class="flex justify-between  border-t-2 pt-2">
            <div>
                <div class="flex items-center ">
                    <div class="flex">
                        <div class="pr-2">
                            <x-heroicon-o-clock class="h-4" />
                        </div>
                    </div>
                    <div class="">
                        <p class="text-gray-900">
                            {{ Carbon::parse($clip['updated_at'])->format('Y-m-d  H:i:s') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="pr-2">
                        <div class="">
                            <x-heroicon-o-lock-open class="h-4" />
                        </div>
                    </div>
                    <div class="">
                        <p class="text-gray-900">
                            @if (!is_array($clip['acls']))
                                {{ $clip['acls'] }}
                            @endif
                        </p>
                    </div>
                </div>
                @if(!is_null($clip['owner']))
                    <div class="flex items-center">
                        <div class="pr-2">
                            <x-heroicon-o-upload class="h-4" />
                        </div>
                        <div class="">
                            <p class="text-gray-900">
                                {{ ($clip['owner']['fullName'])}}
                            </p>
                        </div>
                    </div>
                @endif

                @if(collect($clip['presenters'])->isNotEmpty())
                    <div class="flex items-center">
                        <div class="flex pr-2 items-center">
                            <div class="pr-2">
                                <x-heroicon-o-user class="h-4" />
                            </div>
                            <div class="flex items-center align-middle">
                                @foreach ($clip['presenters'] as $presenter)

                                    <div class="pr-2">
                                        {{ $presenter['presenter_fullName'] }}
                                    </div>
                                    <img src="{{ env('app_url').$presenter['presenter_image_url'] }}" alt=""
                                         class="h-8 rounded-full">
                                @endforeach
                            </div>

                        </div>
                    </div>
                @endif
                <div class="flex items-center">
                    <div class="pr-2">
                        <x-heroicon-o-office-building class="h-4" />
                    </div>
                    <div class="">
                        <p class="text-gray-900">
                            {{ ($clip['organization']['org_name'])}}
                        </p>
                    </div>
                </div>
                @if(!empty($clip['series']))
                    <div class="flex items-center">
                        <div class="flex pr-2 items-center">
                            <div class="pr-2 font-bold">
                                Part of Videoseries :
                            </div>
                            <div class="flex items-center align-middle italic">
                                <a href="{{ route('frontend.series.show', $clip['series']['series_slug']) }}">
                                    @php
                                        if(\Illuminate\Support\Str::length($clip['series']['series_semester']) > 50) {
                                                $semester = 'Multiple Semesters';
                                            }
                                        else {
                                            $semester  = $clip['series']['series_semester'];
                                        }
                                    @endphp
                                    {{ $clip['series']['series_title'].' |'.$semester.' |  SeriesID:'.$clip['series']['series_id'] }}
                                </a>

                            </div>

                        </div>
                    </div>
                @endif
            </div>
            <div>
                <div class="pt-8 flex w-48 h-auto place-items-center justify-center justify-items-center">
                    <img src="{{ $clip['poster']}}"
                         alt="preview image">
                </div>
            </div>
        </div>
    </div>
</figure>
