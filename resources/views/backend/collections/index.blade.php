@extends('layouts.backend')

@section('content')
    <div class="mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl">
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
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table class="min-w-full">
                            <thead class="border-b bg-white">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                    Position
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                    Title
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                    Description
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                    Clips
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($collections->sortBy('position') as $collection)
                                <tr class="@if ($collection->is_public) bg-gray-300 @else bg-white @endif
                                    border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $collection->position }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-light text-gray-900">
                                        {{ $collection->title }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-light text-gray-900">
                                        {{ str()->of($collection->description)->limit(50,' (...)') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-light text-gray-900">
                                        {{ $collection->clips()->count() }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-light text-gray-900">
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
