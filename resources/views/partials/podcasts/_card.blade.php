<div
    class="grid grid-rows-3 grid-flow-col  bg-gray-50 rounded-lg shadow  dark:bg-gray-800
    dark:border-gray-700 items-start">
    <div class="row-span-3">
        <a href="{{ route('frontend.podcasts.show', $podcast) }}" class="m-4 py-2">
            <img class="max-w-fit w-48 rounded-lg sm:rounded-none sm:rounded-l-lg px-2 "
                 @if(!is_null($podcast->image_id))
                     src="{{ asset('images/'.$podcast->cover->file_name) }}"
                 @else
                     src="/podcast-files/covers/PodcastDefaultFAU.png"
                 @endif
                 alt="{{ $podcast->title }} cover image">
        </a>
    </div>

    <div class="col-span-2 w-full pt-4 pl-4 items-start">
        <h3 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
            <a
                @if(str_contains(url()->current(), 'admin'))
                    href="{{ route('podcasts.edit', $podcast) }}">{{ $podcast->title }}
                @else
                    href="{{ route('frontend.podcasts.show', $podcast) }}">{{ $podcast->title }}
                @endif

            </a>
        </h3>
    </div>
    <div class="row-span-2 col-span-1 pl-4">
        <div class="flex float-left">
            <p class="mt-3 mb-4 font-light text-gray-800 dark:text-white float-">
                @if($podcast->description==='')
                    <span class="italic">No description available</span>
                @else
                    {{ Str::limit(removeHtmlElements($podcast->description), 250, ' (...)') }}
                @endif
            </p>
        </div>

        <div class="flex w-full justify-between p-4
        ">
            <div>
                <ul class="flex space-x-4 sm:mt-0">
                    <li>
                        <a href="{{$podcast->website_url}}"
                           class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                            <x-iconoir-www class="w-6 text-black dark:text-white" />
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                            <x-iconoir-spotify class="w-6 dark:text-white" />
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-white hover:text-gray-900 dark:hover:text-white">
                            <x-iconoir-apple-mac class="w-6  text-black dark:text-white" />
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-span-11 flex flex-row space-x-2">
                @can('edit-podcast', $podcast)
                    <div>
                        <a href="{{ route('podcasts.edit', $podcast) }}"
                        >
                            <x-button class="bg-green-500 hover:bg-green-700"
                            >
                                Edit podcast
                            </x-button>
                        </a>
                    </div>
                    <div>
                        <form action="{{ route('podcasts.destroy',$podcast) }}"
                              method="POST"
                        >
                            @method('DELETE')
                            @csrf
                            <x-button type="submit" class="bg-red-500 hover:bg-red-700"
                            >
                                delete podcast
                            </x-button>
                        </form>

                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>
