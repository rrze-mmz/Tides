@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-32 h-screen md:mt-32">
        <div class="flex justify-between pb-2 border-b-2 border-black">
            <h2 class="text-2xl font-bold">{{ $series->title }} [ID: {{ $series->id }}]</h2>
            @can('edit-series', $series)
                <x-form.button :link="$series->adminPath()" type="submit" text="Edit series"/>
            @endcan
        </div>

        <div class="flex flex-col pt-10">
            <h2 class="text-2xl font-semibold">Description</h2>
            <p class="pt-4 leading-loose">
                {!! $series->description !!}
            </p>
        </div>
        <div class="flex justify-around pt-8 pb-3 border-b-2 border-gray-500">

            <div class="flex items-center w-1/4">
                <svg class="w-6 h-6"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                <span class="pl-3">
                    {{$series->clips->sortBy('semester_id')->map(function ($clip){
                                return $clip->semester;
                            })->pluck('name')->unique()->implode(', ')
        }}</span>
            </div>

            <div class="flex items-center w-1/4">
                <svg class="w-6 h-6"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg"
                >
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">

                    </path>
                </svg>
                <span class="pl-3"></span>
            </div>

            <div class="flex items-center w-1/4">
                <svg class="w-6 h-6"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg"
                >
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                    >
                    </path>
                </svg>
                <span class="pl-3"> {{ $series->latestClip?->updated_at }} </span>
            </div>

            <div class="flex items-center w-1/4">
                <svg class="w-6 h-6"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg"
                >
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    ></path>
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274
                      4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                    </path>
                </svg>
                <span class="pl-3"> 0 Views </span>
            </div>

        </div>
        @include('backend.clips.list')
    </main>
@endsection
