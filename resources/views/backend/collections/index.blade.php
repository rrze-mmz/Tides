@extends('layouts.backend')

@section('content')
    <div class="flex justify-between  pb-2 mb-5 font-semibold border-b border-black font-2xl items-center">
        <div class="flex">
            Collections Index
        </div>
        <div class="flex">
            <x-form.button :link="route('collections.create')" type="submit" text="Create a new collection"/>
        </div>
    </div>
    @if ($collections->count() > 0 )
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-white border-b">
                            <tr>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Position
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Title
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Description
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Clips
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($collections->sortBy('position') as $collection)
                                <tr class="@if ($collection->is_public) bg-gray-300 @else bg-white @endif
                                    border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $collection->position }}
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        {{ $collection->title }}
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        {{ str()->of($collection->description)->limit(50,' (...)') }}
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        {{ $collection->clips()->count() }}
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-2">
                                            <x-form.button :link="route('collections.edit',$collection)"
                                                           type="submit"
                                                           text="Edit"
                                            />
                                            <form action="{{ route('collections.destroy', $collection) }}"
                                                  method="POST"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <x-form.button :link="$link=false"
                                                               type="delete"
                                                               color="red"
                                                               text="Delete Collection"
                                                />
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
