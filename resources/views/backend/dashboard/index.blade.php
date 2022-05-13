@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        {{ __('dashboard.welcome to personal dashboard', ['fullName' => auth()->user()->getFullNameAttribute() ]) }}
        !
    </div>
    <div class="flex flex-col px-2 py-2">
        <div>
            <p class="pt-2">
                <span class="mr-2">{{ __('dashboard.start creating new series') }}</span>
                <x-form.button :link="route('series.create')" type="submit" text="{{ __('dashboard.new series') }}"/>
            </p>
        </div>
        <div>
            <p class="pt-2 mt-4">
                <span class="mr-2">{{ __('dashboard.start creating a new clip') }}</span>
                <x-form.button :link="route('clips.create')" type="submit" text="{{ __('dashboard.new clip') }}"/>

            </p>
        </div>
    </div>
    <div class="flex">
        <div class="w-2/3">
            @can('view-opencast-workflows')
                @if($opencastWorkflows->isNotEmpty())
                    @include('backend.dashboard._opencast-workflows',['opencastWorkflows' => $opencastWorkflows])
                @endif
            @endcan

            @include('backend.users.series._layout',['layoutHeader' => __('dashboard.your last series'), 'series'=> $userSeries])

            @include('backend.users.clips._layout',['layoutHeader' => __('dashboard.your last clips'), 'clips'=> $userClips])
        </div>

        <div class="pl-2 w-1/3">
            @include('backend.dashboard._dropzone-files')
        </div>
    </div>
@endsection
