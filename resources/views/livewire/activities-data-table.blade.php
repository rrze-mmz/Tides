<div class="flex flex-col">
    <div class="overflow-x-auto -my-2 sm:-mx-6 lg:-mx-8">
        <div class="inline-block py-2 min-w-full align-middle sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="w-full max-w-lg lg:max-w-xs">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                            <x-heroicon-o-search class="w-5 h-5 text-gray-400"/>
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
                <div class="flex relative items-start">
                    <div class="flex items-center h-5">
                        <input wire:model="series" id="series" type="checkbox"
                               class="w-4 h-4 text-indigo-600 transition duration-150 ease-in-out form-checkbox">
                    </div>
                    <div class="ml-3 text-sm leading-5">
                        <label for="series" class="font-medium text-gray-700">Series</label>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden mt-4 border-b border-gray-200 shadow sm:rounded-lg">

                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <tr>
                        <th
                            class="px-2 py-3 text-left">
                            <div class="flex items-center">
                                <button wire:click="sortBy('id')" class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    ID
                                </button>
                                <x-sort-icon
                                    field="id"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc"/>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <button wire:click="sortBy('created_at')" class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Updated
                                </button>
                                <x-sort-icon
                                    field="created_at"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc"/>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <button wire:click="sortBy('user_id')" class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Full Name
                                </button>
                                <x-sort-icon
                                    field="user_id"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc"/>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <button wire:click="sortBy('content_type')"
                                        class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Type
                                </button>
                                <x-sort-icon
                                    field="content_type"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc"/>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <button wire:click="sortBy('change_message')"
                                        class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Message
                                </button>
                                <x-sort-icon
                                    field="change_message"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc"/>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <button wire:click="sortBy('changes')"
                                        class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Changed
                                </button>
                                <x-sort-icon
                                    field="changes"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc"/>
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($activities as $activity)
                        <tr>
                            <td class="px-2 py-4 w-1/12 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            {{ $activity->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 py-4 w-2/12 whitespace-no-wrap">
                                <div class="flex items-center text-center">
                                    <div class="ml-2">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            {{ $activity->created_at }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 w-2/12 whitespace-no-wrap">
                                <div class="flex items-center text-center">
                                    <div class="ml-1">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            {{ $activity->user_real_name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 w-2/12 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-2">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            <a href="{{ '/admin/'.$activity->content_type.'/'.$activity->object_id}}">
                                                {{ $activity->content_type }} - ID {{ $activity->object_id }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 w-3/12 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-2">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            {{ $activity->change_message }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 w-4/12 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-2">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            @if (!empty($activity->changes) )
                                                @foreach($activity->changes['before'] as $key=>$value)
                                                    <div class="w-full text-red-600">
                                                        {{ '--'. $key. '=>'.$value}}
                                                    </div>
                                                @endforeach
                                                @foreach($activity->changes['after'] as $key=>$value)
                                                    <div class="w-full text-green-600">
                                                        {{ '++'. $key. '=>'.$value}}
                                                    </div>
                                                @endforeach
                                            @else
                                                {{ '-' }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
    <div class="h-96"></div>
</div>
