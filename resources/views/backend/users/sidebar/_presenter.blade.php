@if(!is_null($user->presenter))
    <div
        class="mx-4 h-full w-full rounded-md border bg-white px-4 py-4 font-normal dark:bg-gray-800 dark:border-blue-800">
        <h2 class="mb-3 -ml-5 border-l-4 border-blue-600 dark:border-blue-800 py-4 pl-4 text-xl
    dark:text-white"
        >
            Presenter
        </h2>
        <figure class="md:flex bg-slate-100 rounded-xl p-8  md:p-0 dark:bg-slate-800">
            <img class="w-24 h-24 rounded-full mx-auto mt-10"
                 src="@if(!is_null($user->presenter))
                                             {{ $user->presenter->getImageUrl() }}
                                             @else/images/DummyMann.png>
                                        @endif" alt="">
            <div class="pt-6 md:p-8 text-left space-y-4 dark:text-white">
                <blockquote>
                    <p class="lg:text-lg md:text-md font-normal">
                        {{ $user->presenter->getFullNameAttribute() }}
                    </p>
                    <p class="lg:text-lg md:text-md font-normal pt-4">
                        Number of Series: {{ $user->presenter->series()->count() }}
                    </p>
                    <p class="lg:text-lg md:text-md font-normal pt-4">
                        Number of Clips: {{ $user->presenter->clips()->count() }}
                    </p>
                </blockquote>
            </div>
        </figure>
        <div class="text-sky-500 dark:text-sky-400 pt-10">
            <a href="{{route('presenters.edit', $user->presenter) }}">
                <x-button class="bg-blue-600 hover:bg-blue700"> Go to Presenter page</x-button>
            </a>
        </div>
    </div>
@else

@endif
