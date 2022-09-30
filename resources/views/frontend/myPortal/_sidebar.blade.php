<aside class="w-64"
       aria-label="Sidebar">
    <div class="overflow-y-auto py-4 px-3 bg-gray-50 rounded dark:bg-gray-800 text-white">
        <ul class="space-y-2">
            <li>
                <a href="{{route('frontend.userSettings.edit')}}"
                   class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg
                                   dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                    <x-heroicon-o-adjustments class="flex-shrink-0 w-6 h-6 text-gray-500 transition
                                    duration-75 dark:text-gray-400
                                    group-hover:text-gray-900 dark:group-hover:text-white"
                    />
                    <span class="flex-1 ml-3 whitespace-nowrap">
                                        Portal settings
                                    </span>
                </a>
            </li>
            <li>
                <a href="#"
                   class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg
                                   dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700"
                >
                    <x-heroicon-o-book-open class="w-6 h-6 text-gray-500 transition duration-75
                                    dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"/>
                    <span class="ml-3">
                                        Series subscriptions
                                    </span>
                </a>
            </li>
            <li>
                <a href="#"
                   class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg
                                   dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700"
                >
                    <x-heroicon-o-chat class="flex-shrink-0 w-6 h-6 text-gray-500 transition
                                    duration-75 dark:text-gray-400 group-hover:text-gray-900
                                    dark:group-hover:text-white"/>
                    <span class="flex-1 ml-3 whitespace-nowrap">
                                        Comments
                        </span>
                    <span
                        class="inline-flex justify-center items-center p-3 ml-3 w-3 h-3 text-sm
                                        font-medium text-blue-600 bg-blue-200 rounded-full
                                        dark:bg-blue-900 dark:text-blue-200">
                                        3
                        </span>
                </a>
            </li>
        </ul>
    </div>
</aside>
