<div class="flex content-center justify-center">
    <form method="GET"
          action="{{route('search')}}"
          class="w-3/5">

        <div class="p-2">
            <div
                class="flex flex-col items-center rounded-full bg-gray-50 dark:bg-gray-800 dark:border-white shadow-xl">
                <div class="flex w-full items-center rounded-full">
                    <input class="ml-2 py-2 px-4 w-full leading-tight text-gray-700 dark:text-white
                                        rounded-l-full dark:placeholder-white  dark:focus:border-white
                                        focus:outline-none dark:bg-gray-950"
                           id="term"
                           type="text"
                           name="term"
                           placeholder="{{ __('homepage.Search form placeholder') }}">

                    <div class="p-4">
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="search-series-checkbox"
                                   class="form-checkbox h-5 w-5 text-blue-600"
                                   name="series"
                                   checked
                            />
                            <label for="search-series-checkbox"
                                   class="ml-2 text-black dark:text-white"
                            >
                                Series
                            </label>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="search-clips-checkbox"
                                   class="form-checkbox h-5 w-5 text-blue-600"
                                   name="clips"
                                   checked
                            />
                            <label for="search-clips-checkbox"
                                   class="ml-2 text-black dark:text-white"
                            >
                                Clips
                            </label>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="search-clips-checkbox"
                                   class="form-checkbox h-5 w-5 text-blue-600"
                                   name="clips"
                                @checked(old('channels', ))
                            />
                            <label for="search-clips-checkbox"
                                   class="ml-2 text-black dark:text-white"
                            >
                                Channels
                            </label>
                        </div>
                    </div>
                    <div class="p-4">
                        <button class="flex justify-center items-center p-2 w-8 h-8 text-white bg-gray-600
                                            rounded-full hover:bg-gray-500 focus:outline-none"
                                type="search"
                        >
                            <svg class="h-6 w-6 dar:bg-white"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg"
                            >
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                ></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @error('term')
                <div class="items-center pb-2 justify-content-center">
                    <p class="text-xs text-red-500">{{ $message }}</p>
                </div>
                @enderror
            </div>
        </div>
    </form>
</div>
