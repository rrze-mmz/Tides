<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="max-w-lg w-full lg:max-w-xs">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1
                                                1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                      clip-rule="evenodd">
                                </path>
                            </svg>
                        </div>
                        <input wire:model="search"
                               id="search"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5
                                            bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400
                                            focus:border-blue-300 focus:shadow-outline-blue sm:text-sm transition
                                            duration-150 ease-in-out"
                               placeholder="Search" type="search">
                    </div>
                </div>
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input wire:model="admin" id="admin" type="checkbox"
                               class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                    </div>
                    <div class="ml-3 text-sm leading-5">
                        <label for="admin" class="font-medium text-gray-700">SMP-352</label>
                    </div>
                </div>
            </div>

            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg mt-4">

                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <tr>
                        <th
                            class="px-6 py-3 text-left ">
                            <div class="flex items-center">
                                <button wire:click="sortBy('name')" class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Name
                                </button>
                                <x-sort-icon
                                    field="name"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc"/>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left ">
                            <div class="flex items-center">
                                <button wire:click="sortBy('location')" class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Location
                                </button>
                                <x-sort-icon
                                    field="location"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc"/>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left ">
                            <div class="flex items-center">
                                <button wire:click="sortBy('faculty')" class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Faculty
                                </button>
                                <x-sort-icon
                                    field="faculty"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc"/>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left ">
                            <div class="flex items-center">
                                <button wire:click="sortBy('camera')"
                                        class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Camera
                                </button>
                                <x-sort-icon
                                    field="camera"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc"/>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left ">
                            <div class="flex items-center">
                                <button wire:click="sortBy('power_plug')"
                                        class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Power Plug
                                </button>
                                <x-sort-icon
                                    field="power_plug"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc"/>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left ">
                            <div class="flex items-center">
                                <div
                                    class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Recording Available
                                </div>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left ">
                            <div class="flex items-center">
                                <div
                                    class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Livestream available
                                </div>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left ">
                            <div class="flex items-center">
                                <div
                                    class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Operational
                                </div>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left ">
                            <div class="flex items-center">
                                <div
                                    class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Hybrid
                                </div>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left ">
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
                    @foreach ($devices as $device)
                        <tr>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full"
                                             src="{{ URL::asset('/images/none.jpg') }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm leading-5 font-medium text-gray-900">
                                            {{ $device->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm leading-5 font-medium text-gray-900">
                                            {{ $device->location->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm leading-5 font-medium text-gray-900">
                                            {{ $device->organization->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12  px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    @if (!empty($device->camera_url))
                                        <a href="{{$device->camera_url}}">Kamera</a>
                                    @endif
                                </div>
                            </td>
                            <td class="w-2/12  px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    @if (!empty($device->power_outlet_url))
                                        <a href="{{$device->power_outlet_url}}">Power outlet</a>
                                    @endif
                                </div>
                            </td>
                            <td class="w-2/12  px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    @if ($device->has_recording_func)
                                        <x-heroicon-o-badge-check class="w-6 h-6"/>
                                    @else
                                        <x-heroicon-o-x-circle class="w-6 h-6"/>
                                    @endif
                                </div>
                            </td>
                            <td class="w-2/12  px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    @if ($device->has_livestream_func)
                                        <x-heroicon-o-badge-check class="w-6 h-6"/>
                                    @else
                                        <x-heroicon-o-x-circle class="w-6 h-6"/>
                                    @endif
                                </div>
                            </td>
                            <td class="w-2/12  px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    @if ($device->operational)
                                        <x-heroicon-o-badge-check class="w-6 h-6"/>
                                    @else
                                        <x-heroicon-o-x-circle class="w-6 h-6"/>
                                    @endif
                                </div>
                            </td>
                            <td class="w-2/12  px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    @if ($device->is_hybrid)
                                        <x-heroicon-o-badge-check class="w-6 h-6"/>
                                    @else
                                        <x-heroicon-o-x-circle class="w-6 h-6"/>
                                    @endif
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                <div class="flex space-x-2">
                                    <form action="{{ route('devices.index') }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-form.button :link="$link=false"
                                                       type="delete"
                                                       text="Delete"
                                                       color="red"
                                        />
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $devices->links() }}
            </div>
        </div>
    </div>
    <div class="h-96"></div>
</div>

