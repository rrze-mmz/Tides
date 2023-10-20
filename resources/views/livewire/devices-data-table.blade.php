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
                                    Name
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
                                    Location
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
                                    Faculty
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
                                <button wire:click="sortBy('camera')"
                                        class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Camera
                                </button>
                                <x-sort-icon
                                    field="camera"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc" />
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left">
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
                                    Recording Available
                                </div>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left">
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
                            class="px-6 py-3 text-left">
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
                            class="px-6 py-3 text-left">
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

                    @foreach ($devices as $device)
                        <tr>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full"
                                             src="{{ URL::asset('/images/none.jpg') }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            {{ $device->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            {{ $device->location->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            {{ $device->organization->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    @if (!empty($device->camera_url))
                                        <a href="{{$device->camera_url}}">Kamera</a>
                                    @endif
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    @if (!empty($device->power_outlet_url))
                                        <a href="{{$device->power_outlet_url}}">Power outlet</a>
                                    @endif
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    @if ($device->has_recording_func)
                                        <x-heroicon-o-badge-check class="h-6 w-6" />
                                    @else
                                        <x-heroicon-o-x-circle class="h-6 w-6" />
                                    @endif
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    @if ($device->has_livestream_func)
                                        <x-heroicon-o-badge-check class="h-6 w-6" />
                                    @else
                                        <x-heroicon-o-x-circle class="h-6 w-6" />
                                    @endif
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    @if ($device->operational)
                                        <x-heroicon-o-badge-check class="h-6 w-6" />
                                    @else
                                        <x-heroicon-o-x-circle class="h-6 w-6" />
                                    @endif
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    @if ($device->is_hybrid)
                                        <x-heroicon-o-badge-check class="h-6 w-6" />
                                    @else
                                        <x-heroicon-o-x-circle class="h-6 w-6" />
                                    @endif
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 text-right text-sm font-medium leading-5 whitespace-no-wrap">
                                <div class="flex space-x-2">
                                    <a href="{{route('devices.edit',$device)}}">
                                        <x-button type="button" class="bg-green-600 hover:bg-green-700">
                                            {{__('common.actions.edit')}}
                                        </x-button>
                                    </a>
                                    <form action="{{ route('devices.destroy', $device) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button class="bg-red-600 hover:bg-red-700">
                                            {{__('common.actions.delete')}}
                                        </x-button>

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

