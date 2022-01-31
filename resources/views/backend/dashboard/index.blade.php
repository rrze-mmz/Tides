@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Welcome to your personal dashboard, {{ auth()->user()->getFullNameAttribute() }} !
    </div>
    <div class="flex flex-col px-2 py-2">
        <div>
            <p class="pt-2">
                <span class="mr-2">Start by creating a new series (series is a collection of clips)</span>
                <x-form.button :link="route('series.create')" type="submit" text="New series"/>
            </p>
        </div>
        <div>
            <p class="pt-2 mt-4">
                <span class="mr-2">Start by creating a new video clip</span>
                <x-form.button :link="route('clips.create')" type="submit" text="New clip"/>

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

            @include('backend.users.series._layout',['layoutHeader' => 'Your Latest Series', 'series'=> $userSeries])

            @include('backend.users.clips._layout',['layoutHeader' => 'Your Latest Clips', 'clips'=> $userClips])
        </div>

        <div class="pl-2 w-1/3">
            @include('backend.dashboard._dropzone-files')
        </div>
    </div>
@endsection
