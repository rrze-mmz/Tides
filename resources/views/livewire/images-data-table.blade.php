<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="max-w-lg w-full lg:max-w-xs">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-heroicon-o-search class="h-5 w-5 text-gray-400"/>
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
            </div>

            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg mt-4">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <tr>
                        <th
                            class="px-6 py-3 text-left ">
                            <div class="flex items-center">
                                Image
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left ">
                            <div class="flex items-center">
                                <button wire:click="sortBy('description')"
                                        class="bg-gray-50 text-xs leading-4 font-medium text-gray-500 uppercase
                                        tracking-wider"
                                >
                                    Description
                                </button>
                                <x-sort-icon
                                    field="description"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc"/>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left ">
                            <div class="flex items-center">
                                <button wire:click="sortBy('file_name')" class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    File name
                                </button>
                                <x-sort-icon
                                    field="file_name"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc"/>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left ">
                            <div class="flex items-center">
                                File size
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left ">
                            <div class="flex items-center">
                                <button wire:click="sortBy('created_at')"
                                        class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Created at
                                </button>
                                <x-sort-icon
                                    field="created_at"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc"/>
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left ">
                            <div class="flex items-center">
                                <button wire:click="sortBy('updated_at')"
                                        class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Updated at
                                </button>
                                <x-sort-icon
                                    field="updated_at"
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
                                    Actions
                                </div>
                            </div>
                        </th>
                    </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">

                    @forelse ($images as $image)
                        <tr>
                            <td class="w-1/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <img class="h-20 rounded-[21px]"
                                             src="{{ URL::asset('/images/'.$image->file_name) }}"
                                             alt="{{ $image->description }}">
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm leading-5 font-medium text-gray-900">
                                            {{ $image->description }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm leading-5 font-medium text-gray-900">
                                            {{ $image->file_name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm leading-5 font-medium text-gray-900">
                                            {{ humanFileSizeFormat($image->file_size ?? 'null') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12  px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    {{ $image->create_at?->diffForHumans() }}
                                </div>
                            </td>
                            <td class="w-2/12  px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    {{ $image->updated_at?->diffForHumans() }}
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{route('images.edit', $image)}}">
                                        <x-button type="button" class="bg-green-600 hover:bg-green-700">
                                            {{__('common.actions.edit')}}
                                        </x-button>
                                    </a>
                                    <form action="{{ route('images.destroy', $image) }}"
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
                    @empty
                        <tr>
                            <td class="w-full px-6 py-4 whitespace-no-wrap text-center
                                        items-center place-content-center text-sm leading-5 font-medium "
                                colspan="7"
                            >
                                <div class="flex">
                                    No images found. Please create one
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $images->links() }}
            </div>
        </div>
    </div>
    <div class="h-96"></div>
</div>

