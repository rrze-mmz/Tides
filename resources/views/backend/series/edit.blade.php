@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Edit {{ $series->title }} [ ID : {{ $series->id }}]<span
            class="text-xs italic pl-2 pt-1"> created at {{$series->created_at }} </span>
    </div>
    <div class="flex justify-center content-center content-between py-2 px-2">
        <form action="{{ $series->adminPath() }}"
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
                                 :value="$series->description"
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

                <x-form.toggle-button :value="$series->isPublic"
                                      label="Public available"
                                      field-name="isPublic"
                />
            </div>

            <div class="pt-10">
                <x-form.button :link="$link=false" type="submit" text="Update Series"/>
            </div>

        </form>

        @if(auth()->user()->isAdmin())
            <div class="w-1/5">
                Series owner is {{ $series->owner->getFullNameAttribute() }}
            </div>
        @endif

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
                    @include('backend.clips.options.edit')
                    @include('backend.clips.list')
                </div>
                <div x-show="activeTab === 2" id="opencast">
                    @if($opencastSeriesInfo->isNotEmpty())
                        @include('backend.clips.opencast.workflows')
                    @endif
                </div>
                <div x-show="activeTab === 3">Tab 3 Content show Lorem ipsum dolor sit amet consectetur
                    adipisicing elit. Amet,
                    distinctio
                    voluptas quis cum reprehenderit libero ea quidem voluptatem sunt suscipit, excepturi, tenetur
                    assumenda sequi eius minus temporibus earum odit soluta.
                </div>
            </div>
        </div>
    </div>
@endsection
