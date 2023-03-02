@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black text-2xl flex-col">
        <div class="font-semibold">
            {{ $series->title }} [ ID : {{ $series->id }}]
        </div>
        <div>
     <span
         class="text-sm italic"> created at {{$series->created_at }} </span>
        </div>

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
                              label="{{__('common.forms.title')}}"
                              :full-col="true"
                              :required="true"
                />

                <x-form.textarea field-name="description"
                                 :value="strip_tags($series->description)"
                                 label="{{__('common.forms.description')}}"
                />

                <x-form.input field-name="opencast_series_id"
                              input-type="text"
                              :value="$series->opencast_series_id"
                              label="{{__('common.forms.Opencast series ID')}}"
                              :full-col="true"
                              :disabled="true"
                              :required="true"
                />

                <x-form.select2-single field-name="organization_id"
                                       label="{{__('common.forms.organization')}}"
                                       select-class="select2-tides-organization"
                                       model="organization"
                                       :selectedItem="$series->organization_id"
                />

                <x-form.select2-multiple field-name="presenters"
                                         :model="$series"
                                         label="{{trans_choice('common.menu.presenter',2)}}"
                                         select-class="select2-tides-presenters"
                                         :items="$series->presenters"/>

                <x-form.password field-name="password"
                                 :value="$series->password"
                                 label="{{__('common.password')}}"
                                 :full-col="true"
                />

                <x-form.toggle-button :value="$series->is_public"
                                      label="{{__('common.forms.public')}}"
                                      field-name="is_public"
                />
            </div>
            @can('update-series', $series)
                <div class="pt-10">
                    <x-button class="bg-blue-600 hover:bg-blue-700">
                        {{__('series.backend.Update Series')}}
                    </x-button>
                </div>
            @endcan
        </form>

        <div class="space-y-5 w-1/5 h-full pr-4">
            @include('backend.series.sidebar._series-owner')
            @can('delete-series',$series)
                @include('backend.series.sidebar._invite')
            @endif
            @include('backend.documents.upload',['resource' => $series ])
            @include('backend.images._card',['model'=> $series, 'type'=> 'series' ])
        </div>
    </div>

    <div class="flex pt-8 pb-2  font-2xl w-full ">
        <div x-data="{
            activeTab:1,
            activeClass: 'inline-block px-4 py-2 bg-blue-800  rounded-lg',
            inactiveClass : 'inline-block px-4 py-2 bg-blue-500  rounded-lg'
         }" class="w-full">
            <ul class="flex space-x-4  pt-8 pb-2 text-white border-b border-black ">
                <li>
                    <a href="#actions"
                       x-on:click="activeTab = 1"
                       :class="activeTab === 1 ? activeClass : inactiveClass"
                    >
                        {{ __('series.common.actions') }}
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
                    <a href="#moreActions" x-on:click="activeTab = 3"
                       :class="activeTab === 3 ? activeClass : inactiveClass"
                    >
                        More actions
                    </a>
                </li>
                <li>
                    <a href="#comments-section" x-on:click="activeTab = 4"
                       :class="activeTab === 4 ? activeClass : inactiveClass"
                    >
                        Comments
                    </a>
                </li>
            </ul>
            <div class="mt-6 ">
                <div x-show="activeTab === 1" id="actions" class="w-full ">
                    @include('backend.series.buttons.actions')
                    @include('backend.clips.list')
                </div>
                <div x-show="activeTab === 2" id="opencast">
                    @include('backend.series.tabs.opencast.index')

                </div>
                <div x-show="activeTab === 3" id="moreActions">
                    @include('backend.series.buttons.more-options')
                </div>
                <div x-show="activeTab === 4" id="comments-section">
                    <div class="flex flex-col pt-10">
                        <h2 class="text-2xl font-semibold pb-2 border-b-2 border-black">
                            Backend {{ __('clip.frontend.comments') }}
                        </h2>
                        <livewire:comments-section :model="$series" :type="'backend'"/>
                        @livewireScripts

                    </div>
                </div>
            </div>
        </div>
@endsection
