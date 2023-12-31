@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 font-semibold font-2xl">
        {{ __('dashboard.welcome to personal dashboard', ['fullName' => auth()->user()->getFullNameAttribute() ]) }}
        !
    </div>
    <div class="flex flex-col px-2 py-2">
        <div>
            <p class="pt-2">
                <span class="mr-2">{{ __('dashboard.start creating new series') }}</span>
                <a href="{{route('series.create')}}">
                    <x-button class="bg-blue-600 hover:bg-blue-700">
                        {{ __('dashboard.new series') }}
                    </x-button>
                </a>
            </p>
        </div>
        <div>
            <p class="mt-4 pt-2">
                <span class="mr-2">{{ __('dashboard.start creating a new clip') }}</span>
                <a href="{{route('clips.create')}}">
                    <x-button class="bg-blue-600 hover:bg-blue-700">
                        {{ __('dashboard.new clip') }}
                    </x-button>
                </a>
            </p>
        </div>
    </div>
    <div class="flex">
        <div class="@if(count($files) > 0)) w-2/3 @else w-full @endif">
            @if($opencastEvents->isNotEmpty())
                @include('backend.dashboard._opencast-workflows',['opencastEvents' => $opencastEvents])
            @endif

            @include('backend.users.series._layout',['layoutHeader' => __('dashboard.your last series'), 'series'=> $userSeries])

            @include('backend.users.clips._layout',['layoutHeader' => __('dashboard.your last clips'), 'clips'=> $userClips])
        </div>

        @if(count($files) > 0 )
            <div class="w-1/3 pl-2">
                @include('backend.dashboard._dropzone-files')
            </div>
        @endif
    </div>
@endsection
