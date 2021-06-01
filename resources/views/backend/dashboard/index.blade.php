@extends('layouts.backend')

@section('content')
                <div class="flex pb-2 font-semibold border-b border-black font-2xl">
                    Welcome {{ auth()->user()->name }} !!  This is your personal Dashboard
                </div>
                <div class="flex flex-col py-2 px-2">
                    <div>
                        <p class="pt-2">
                            <span class="mr-2">Start by creating a new series (series are a collection of clips)</span>
                            <x-form.button  :link="route('series.create')" type="submit" text="New series"/>
                        </p>
                    </div>
                    <div>
                        <p class="pt-2 mt-4">
                            <span class="mr-2">Start by creating a new video clip</span>
                            <x-form.button  :link="route('clips.create')" type="submit" text="New clip"/>

                        </p>
                    </div>
                </div>
                <div class="flex">
                    <div class="w-2/3">
                        <div class="pt-10 pb-2 font-semibold border-b border-black font-2xl">
                            Your Latest Series
                        </div>
                        <div class="grid grid-cols-3 gap-4 pt-8 h48">
                            @forelse($series as $single)
                                @include('backend.series._card',['series'=> $single])
                            @empty
                                No series found
                            @endforelse
                        </div>

                        <div class="pt-10 pb-2 font-semibold border-b border-black font-2xl">
                            Your Latest Clips
                        </div>
                        <div class="grid grid-cols-3 gap-4 pt-8 h48">
                            @forelse($clips as $clip)
                                @include('backend.clips._card',['clip'=> $clip])
                            @empty
                                No clips found
                            @endforelse
                        </div>
                    </div>

                    <div class="pl-2 w-1/3">
                        @include('backend.dashboard._dropzone-files')
                    </div>
                </div>
@endsection
