@extends('layouts.backend')

@section('content')
    <div class="mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
                dark:text-white dark:border-white"
    >
        <div class="flex">
            Collections Index
        </div>
        <div class="flex">
            <x-form.button :link="route('collections.create')" type="submit" text="Create a new collection" />
        </div>
    </div>
    @if ($collections->count() > 0 )
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="mt-4 overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                        <table class="min-w-full">
                            <thead class="border-b bg-white text-gray-900 dark:bg-gray-800 dark:text-white font-normal">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-sm">
                                    Position
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-sm ">
                                    Title
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-sm ">
                                    Description
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-sm ">
                                    Clips
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-sm ">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($collections->sortBy('position') as $collection)
                                <tr class="@if ($collection->is_public) bg-gray-300 dark:bg-slate-800 @else bg-white @endif
                                    border-b transition duration-300  text-gray-900 ease-in-out hover:bg-gray-100
                                    text-sm leading-5 dark:text-white ">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        {{ $collection->position }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-light">
                                        {{ $collection->title }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-light">
                                        {{ str()->of($collection->description)->limit(50,' (...)') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-light">
                                        {{ $collection->clips()->count() }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-light">
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
