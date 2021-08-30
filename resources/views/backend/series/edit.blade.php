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
                    <x-form.button :link="$link=false" type="submit" text="Update Series" />
                </div>

            </form>

            @if(auth()->user()->isAdmin())
                <div class="w-1/5">
                    Series owner is {{ $series->owner->getFullNameAttribute() }}
                </div>
            @endif

        </div>

        <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
            More actions
        </div>
        <div class="flex items-center pt-3 space-x-6">
            <x-form.button :link="route('frontend.series.show',$series)" type="submit" text="Go to public page"/>

            <x-form.button :link="route('series.clip.create',$series)" type="submit" text="Add new clip"/>

            <form action="{{$series->adminPath()}}"
                  method="POST">
                @csrf
                @method('DELETE')
                <x-form.button :link="$link=false" type="delete" text="Delete Series"/>
            </form>
        </div>
        @if(isset($opencastSeriesRunningWorkflows['workflows']) && $opencastSeriesRunningWorkflows['workflows']['totalCount'] > 0)
        <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
            Opencast running events
        </div>
            <ul>
                @foreach($opencastSeriesRunningWorkflows['workflows']['workflow'] as $workflow)
                    <li>
                        {{ $workflow['mediapackage']['title'] }}
                    </li>
                @endforeach
            </ul>
        @endif
        @include('backend.clips.list')

    </div>
@endsection
