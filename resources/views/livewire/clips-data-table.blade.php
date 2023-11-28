@php use App\Enums\Acl; @endphp
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
                               class="block w-full pl-10 pr-3 py-2 my-2 border border-gray-300 rounded-md leading-5
                                            bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400
                                            focus:border-blue-300 focus:shadow-outline-blue sm:text-sm transition
                                            duration-150 ease-in-out"
                               placeholder="Search" type="search">
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <div class="relative flex items-start pr-4 ">
                        <div class="flex h-5 items-center">
                            <input wire:model.live="userClips" id="user-clips" type="checkbox"
                                   class="h-4 w-4 text-indigo-600 transition duration-150 ease-in-out form-checkbox">
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <label for="admin" class="font-medium text-gray-700">My clips</label>
                        </div>
                    </div>
                    <div class="relative flex items-start">
                        <select wire:model.live="selectedSemesterID">
                            <option value="">Select an Option</option>
                            @foreach ($semestersList as $semester)
                                <option value="{{ $semester->id }}">
                                    {{ $semester->name }}
                                </option>
                            @endforeach
                        </select>
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
                                    field="title"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc" />
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <button wire:click="sortBy('series_id')" class="bg-gray-50 text-xs leading-4 font-medium
                                                    text-gray-500 uppercase tracking-wider"
                                >
                                    Series
                                </button>
                                <x-sort-icon
                                    field="series_id"
                                    :sortField="$sortField"
                                    :sortAsc="$sortAsc" />
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <button wire:click="sortBy('semester_id')" class="bg-gray-50 text-xs leading-4 font-medium
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

                    @foreach ($clips as $clip)
                        <tr>
                            <td class="w-4/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="h-12 w-24 flex-shrink-0">
                                        <img class="h-12 w-24 "
                                             src="{{fetchClipPoster($clip->latestAsset?->player_preview) }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            {{ $clip->title.' / ID:'.$clip->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">

                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            @if(!is_null($clip->series_id))
                                                @can('edit-clips', $clip)
                                                    <a href="{{ route('series.edit', $clip->series) }}">
                                                        {{ $clip->series->title }}
                                                    </a>
                                                @else
                                                    <a href="{{ route('frontend.series.show', $clip->series) }}">
                                                        {{ $clip->series->title }}
                                                    </a>
                                                @endcan
                                            @else
                                                {{ 'No Series' }}
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            {{ $clip->semester->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            <div class="pr-2">
                                                @if(!$clip->acls->contains(Acl::PUBLIC))
                                                    @can('watch-video', $clip)
                                                        <x-heroicon-o-lock-open class="h-4 w-4 text-green-500" />
                                                        <span class="sr-only">Unlock clip</span>
                                                    @else
                                                        <x-heroicon-o-lock-closed class="h-4 w-4 text-red-700" />
                                                        <span class="sr-only">Lock clip</span>
                                                    @endcan
                                                @endif
                                            </div>
                                            {{ $clip->acls->pluck('name')->implode(', ') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            {{  $clip->organisation->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="w-2/12 px-6 py-4 whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">
                                            @if($clip->presenters->isNotEmpty())
                                                <div class="flex items-center">
                                                    <div class="flex pr-2 items-center">
                                                        <div class="pr-2">
                                                            <x-heroicon-o-user class="h-4" />
                                                        </div>
                                                        <div class="flex items-center align-middle">
                                                            {{ $clip->presenters
                                                                       ->map(function($presenter){
                                                                           return $presenter->getFullNameAttribute();
                                                                       })->implode(',') }}
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
                                    <a href="{{route('frontend.clips.show',$clip)}}">
                                        <x-button type="button" class="bg-green-600 hover:bg-green-700">
                                            {{__('common.actions.show')}}
                                        </x-button>
                                    </a>
                                    @can('edit-clips', $clip)
                                        <a href="{{route('clips.edit',$clip)}}">
                                            <x-button type="button" class="bg-green-600 hover:bg-green-700">
                                                {{__('common.actions.edit')}}
                                            </x-button>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-8">
                {{ $clips->links() }}
            </div>
        </div>
    </div>
    <div class="h-96"></div>
</div>
