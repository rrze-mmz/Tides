@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Edit {{ $series->title }} [ ID : {{ $series->id }}]<span
            class="text-xs italic pl-2 pt-1"> created at {{$series->created_at }} </span>
    </div>
    <div class="flex justify-center content-center content-between py-2 px-2">
        <form action="{{ route('series.update',$series) }}"
              method="POST"
              class=" @if(auth()->user()->isAdmin()) w-4/5 @else w-full @endif"
        >
            @csrf
            @method('PATCH')

            <div class="flex flex-col gap-3">

                <x-form.input field-name="title"
                              input-type="text"
                              :value="$series->title"
                              label="Title"
                              :full-col="true"
                              :required="true"
                />

                <x-form.textarea field-name="description"
                                 :value="strip_tags($series->description)"
                                 label="Description"
                />

                <x-form.input field-name="opencast_series_id"
                              input-type="text"
                              :value="$series->opencast_series_id"
                              label="Opencast Series ID"
                              :full-col="true"
                              :disabled="true"
                              :required="true"
                />

                <x-form.select2-single field-name="organization_id"
                                       label="Organization"
                                       select-class="select2-tides-organization"
                                       model="organization"
                                       :selectedItem="$series->organization_id"
                />

                <x-form.select2-multiple field-name="presenters"
                                         :model="$series"
                                         label="Presenters"
                                         select-class="select2-tides-presenters"
                                         :items="$series->presenters"/>

                <x-form.password field-name="password"
                                 :value="$series->password"
                                 label="Password"
                                 :full-col="true"
                />

                <x-form.toggle-button :value="$series->is_public"
                                      label="Public available"
                                      field-name="is_public"
                />
            </div>
            @can('update-series', $series)
                <div class="pt-10">
                    <x-form.button :link="$link=false" type="submit" text="Update Series"/>
                </div>
            @endcan
        </form>

        <div class="space-y-5 w-1/5 h-full">
            @include('backend.series.sidebar._series-owner')
            @can('delete-series',$series)
                @include('backend.series.sidebar._invite')
            @endif
            @include('backend.documents.upload',['resource' => $series ])
        </div>

    </div>

    <div class="flex pt-8 pb-2  font-2xl w-full">
        <div x-data="{
            activeTab:1,
            activeClass: 'inline-block px-4 py-2 bg-blue-800',
            inactiveClass : 'inline-block px-4 py-2 bg-blue-500'
         }" class="w-full">
            <ul class="flex space-x-1  pt-8 pb-2 text-white border-b border-black">
                <li>
                    <a href="#actions"
                       x-on:click="activeTab = 1"
                       :class="activeTab === 1 ? activeClass : inactiveClass"
                    >
                        Actions
                    </a>
                </li>
                @if(isset($opencastSeriesInfo['health']) && $opencastSeriesInfo['health'])
                    <li>
                        <a href="#opencast" x-on:click="activeTab = 2"
                           :class="activeTab === 2 ? activeClass : inactiveClass"
                        >
                            Opencast
                        </a>
                    </li>
                @endif
                <li>
                    <a href="#" x-on:click="activeTab = 3"
                       :class="activeTab === 3 ? activeClass : inactiveClass"
                    >
                        More actions
                    </a>
                </li>
            </ul>
            <div class="mt-6 ">
                <div x-show="activeTab === 1" id="actions" class="w-full">
                    @include('backend.series.buttons.actions')
                    @include('backend.clips.list')
                </div>
                <div x-show="activeTab === 2" id="opencast">
                    @if($opencastSeriesInfo->isNotEmpty())
                        @include('backend.dashboard._opencast-workflows',[
                                    'opencastWorkflows' => $opencastSeriesInfo])
                    @endif
                </div>
                <div x-show="activeTab === 3">
                    @include('backend.series.buttons.more-options')
                </div>
            </div>
        </div>
@endsection
