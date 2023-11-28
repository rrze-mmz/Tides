<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="w-full max-w-lg lg:max-w-xs">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <x-heroicon-o-search class="h-5 w-5 text-gray-400" />
                        </div>
                        <input wire:model.live="search"
                               id="search"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5
                                            bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400
                                            focus:border-blue-300 focus:shadow-outline-blue sm:text-sm transition
                                            duration-150 ease-in-out"
                               placeholder="Search" type="search">
                    </div>
                </div>
            </div>

            <div class="mt-4 overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <tr>
                        <th
                            class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <button wire:click="sortBy('name')" class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Title
                                </button>
                                <x-sort-icon
                                    field="name"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc" />
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <button wire:click="sortBy('location')" class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Semester
                                </button>
                                <x-sort-icon
                                    field="location"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc" />
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <button wire:click="sortBy('faculty')" class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Acl
                                </button>
                                <x-sort-icon
                                    field="faculty"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc" />
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <button wire:click="sortBy('faculty')" class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Organization
                                </button>
                                <x-sort-icon
                                    field="faculty"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc" />
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <button wire:click="sortBy('faculty')" class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Presenters
                                </button>
                                <x-sort-icon
                                    field="faculty"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc" />
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <div
                                    class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Actions
                                </div>
                            </div>
                        </th>
                    </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">

                    @foreach ($searchResults['series']['hits']['hits'] as $series)
                        <tr>
                            <td class="w-4/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="h-12 w-24 flex-shrink-0">
                                        <img class="h-12 w-24 "
                                             src="{{ $series['_source']['poster'] }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            {{ $series['_source']['title'].' |'.$series['_source']['semester'].' |  SeriesID:'.$series['_source']['id'] }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            {{ $series['_source']['semester'] }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            {{ $series['_source']['acls']  }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            {{ ($series['_source']['organization']['org_name'])}}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            @if(collect($series['_source']['presenters'])->isNotEmpty())
                                                <div class="flex items-center">
                                                    <div class="flex pr-2 items-center">
                                                        <div class="pr-2">
                                                            <x-heroicon-o-user class="h-4" />
                                                        </div>
                                                        <div class="flex items-center align-middle">
                                                            @foreach ($series['_source']['presenters'] as $presenter)

                                                                <div class="pr-2">
                                                                    {{ $presenter['presenter_fullName'] }}
                                                                </div>
                                                                <img
                                                                    src="{{ env('app_url').$presenter['presenter_image_url'] }}"
                                                                    alt=""
                                                                    class="h-8 rounded-full">
                                                            @endforeach
                                                        </div>

                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 text-right text-sm font-medium leading-5 whitespace-no-wrap">
                                <div class="flex space-x-2">
                                    <a href="{{route('series.edit',$series['_source']['slug'])}}">
                                        <x-button type="button" class="bg-green-600 hover:bg-green-700">
                                            {{__('common.actions.edit')}}
                                        </x-button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="h-96"></div>
</div>
