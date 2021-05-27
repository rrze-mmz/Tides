@extends('layouts.backend')

@section('content')
        @if($status->isNotEmpty())
            <div class="flex pb-2 font-semibold border-b border-black font-2xl align-items-center items-center">
                <div class="pr-4">
                  Opencast status is
                </div>
                @if($status['status'] === 'pass')
                    <svg class="w-6 h-6 bg-green-700 rounded text-white"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg"
                    >
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7"
                        ></path>
                    </svg>
                @else
                    <svg class="w-6 h-6 bg-green-700 rounded text-white"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg"
                    >
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                        >
                        </path>
                    </svg>
                @endif
            </div>

            <div class="flex flex-col space-y-4 pt-4">
                <div class="flex">
                       <div>
                           Opencast version :
                       </div>
                        <div class="font-bold">
                            <span class="pl-4 font-bold"> {{$status['releaseId']}} </span>
                        </div>
                </div>

                <div class="flex">
                    <div>
                        Description :
                    </div>
                    <div class="font-bold">
                        <span class="pl-4 font-bold"> {{$status['description']}} </span>
                    </div>
                </div>
            </div>
        @else
            <div class="flex pb-2 font-semibold border-b border-black font-2xl align-items-center items-center">
                Oops seems like you Opencast connection is not available. Please check that you configure your
                server properly or your Opencast admin node is online
            </div>
        @endif
@endsection
