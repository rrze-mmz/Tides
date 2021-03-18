<div class="flex justify-center content-center">
    <form method="GET"
          action="search"
          class="w-3/5">

        <div class="p-2">
            <div class="flex items-center flex-col  bg-white rounded-full shadow-xl">
                <div class="flex items-center rounded-full w-full">
                        <input class="ml-2 py-2 px-4 w-full leading-tight text-gray-700 rounded-l-full focus:outline-none"
                               id="term"
                               type="text"
                               name="term"
                               placeholder="Search">

                        <div class="p-4">
                            <button class="flex justify-center items-center p-2 w-8 h-8 text-white bg-gray-600 rounded-full hover:bg-gray-500 focus:outline-none"
                                    type="submit"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                </div>
                @error('term')
                <div class="pb-2 items-center justify-content-center ">
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                </div>
                @enderror
            </div>
        </div>
    </form>
</div>
