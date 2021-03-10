{{--        Search form--}}
<div class="flex justify-center justify-center content-center">
    <form method="POST"
          action="/"
          class="w-3/5">
        @csrf
        <div class="p-2">
            <div class="bg-white flex items-center rounded-full shadow-xl">
                <input class="rounded-l-full w-full py-2 px-6 text-gray-700 leading-tight focus:outline-none"
                       id="search"
                       type="text"
                       placeholder="Search">

                <div class="p-4">
                    <button class="bg-gray-600 text-white rounded-full p-2 hover:bg-gray-500 focus:outline-none w-8 h-8 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
