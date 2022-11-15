@php use App\Enums\Acl; @endphp
@extends('layouts.backend')

@section('content')
    <div class="flex-row  w-full pb-2 border-b border-black font-2xl">
        <div class="flex justify-between items-center  w-full">
            <div class="">
                <span class="text-2xl"> [ ID: {{ $clip->id }} ] {{ $clip->title }}</span>
            </div>
            <div class="flex space-x-2">
                @if(!is_null($previousNextClipCollection->get('previousClip')))
                    <x-form.button :link="$previousNextClipCollection->get('previousClip')->adminPath()"
                                   type="submit"
                                   text="Previous"
                    />
                @endif

                @if(!is_null($previousNextClipCollection->get('nextClip')))
                    <x-form.button :link="$previousNextClipCollection->get('nextClip')->adminPath()"
                                   type="submit"
                                   text="Next"
                    />
                @endif
            </div>
        </div>
        <div class="flex font-light text-sm italic pt-2">
            <span
                class="pl-2"> created
                @if(!is_null($clip->owner_id))
                    by {{ $clip->owner->getFullNameAttribute() }} ({{ $clip->owner->username }})
                @endif
                at {{$clip->created_at}}</span>
        </div>
    </div>
    <div class="flex py-2 px-2">
        <form action="{{ route('clips.update', $clip) }}"
              method="POST"
              class="w-4/5"
        >
            @csrf
            @method('PATCH')

            <div class="flex flex-col gap-3">

                <x-form.input field-name="episode"
                              input-type="number"
                              :value="$clip->episode"
                              label="Episode"
                              :full-col="false"
                              :required="true"/>

                <x-form.datepicker field-name="recording_date"
                                   label="Recording Date"
                                   :full-col="false"
                                   :value="$clip->recording_date"/>

                <x-form.input field-name="title"
                              input-type="text"
                              :value="$clip->title"
                              label="Title"
                              :fullCol="true"
                              :required="true"/>

                <x-form.textarea field-name="description"
                                 :value="$clip->description"
                                 label="Description"/>

                <x-form.select2-single field-name="organization_id"
                                       label="Organization"
                                       select-class="select2-tides-organization"
                                       model="organization"
                                       :selectedItem="$clip->organization_id"
                />

                <x-form.select2-single field-name="language_id"
                                       label="Language"
                                       select-class="select2-tides"
                                       model="language"
                                       :selectedItem="$clip->lanugage_id"
                />

                <div class="border-solid   border-b-black border-b mb-2 pb-2 font-bold text-left text-xl">
                    Metadata
                </div>

                <x-form.select2-single field-name="context_id"
                                       label="Context"
                                       select-class="select2-tides"
                                       model="context"
                                       :selectedItem="$clip->context_id"
                />
                <x-form.select2-single field-name="format_id"
                                       label="Format"
                                       select-class="select2-tides"
                                       model="format"
                                       :selectedItem="$clip->format_id"
                />
                <x-form.select2-single field-name="type_id"
                                       label="Type"
                                       select-class="select2-tides"
                                       model="type"
                                       :selectedItem="$clip->type_id"
                />

                <x-form.select2-single field-name="semester_id"
                                       label="Semester"
                                       select-class="select2-tides"
                                       model="semester"
                                       :selectedItem="$clip->semester_id"
                />

                <x-form.select2-multiple field-name="presenters"
                                         :model="$clip"
                                         label="Presenters"
                                         select-class="select2-tides"
                                         :items="$clip->presenters"/>

                <x-form.select2-multiple field-name="tags"
                                         :model="$clip"
                                         label="Tags"
                                         select-class="select2-tides-tags"
                                         :items="$clip->tags"/>

                <x-form.select2-multiple field-name="acls"
                                         :model="$clip"
                                         label="Accessible via"
                                         select-class="select2-tides"/>

                <x-form.password field-name="password"
                                 :value="$clip->password"
                                 label="Password"
                                 :full-col="true"
                />

                <x-form.toggle-button :value="$clip->allow_comments"
                                      label="Allow comments"
                                      field-name="allow_comments"
                />

                <x-form.toggle-button :value="$clip->is_public"
                                      label="Public available"
                                      field-name="is_public"
                />

            </div>

            <x-form.button :link="$link=false"
                           type="submit"
                           text="Save"/>
        </form>

        <div class="space-y-5 w-1/5 h-full pr-4">
            @if(! is_null($clip->series_id) )
                @include('backend.clips.sidebar._series-options')
            @else
                @include('backend.clips.sidebar._assign-series')
            @endif

            @include('backend.clips.sidebar._upload-video')

            @if ($opencastConnectionCollection['status']==='pass')
                @include('backend.clips.sidebar._ingest-video')
            @endif

            @if(auth()->user()->isAdmin() && $clip->acls->pluck('id')->contains(Acl::LMS()))
                @include('backend.clips.sidebar._lms_test_link')
            @endif

            @include('backend.documents.upload',['resource'=> $clip ])
        </div>
    </div>

    <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
        More actions
    </div>
    <div class="flex items-center pt-3 space-x-6">
        <x-form.button :link="route('frontend.clips.show',$clip)"
                       type="submit"
                       text="Go to public page"
        />

        @if ($clip->assets()->count())
            <x-form.button :link="route('admin.clips.triggerSmilFiles', $clip)"
                           type="submit"
                           text="Trigger smil files"
            />
        @endif

        <x-form.button :link="route('admin.clips.dropzone.listFiles', $clip)"
                       type="submit"
                       text=" Transfer files from drop zone"
        />

        @if($opencastConnectionCollection['status']==='pass')
            <x-form.button :link="route('admin.clips.opencast.listEvents', $clip)"
                           type="submit"
                           text=" Transfer files from Opencast"
            />
        @endif

        <form action="{{ route('clips.destroy',$clip) }}"
              method="POST"
        >
            @csrf
            @method('DELETE')

            <button type="delete" class="inline-flex items-center px-4 py-1 border border-transparent text-base leading-6
                                font-medium rounded-md text-white
                        bg-red-600  focus:shadow-outline-indigo hover:bg-red-700
                        hover:shadow-lg ">
                Delete Clip
            </button>
        </form>
    </div>

    <div x-show="activeTab === 4" id="comments">
        <div class="flex flex-col pt-10">
            <h2 class="text-2xl font-semibold pb-2 border-b-2 border-black">
                Backend {{ __('clip.frontend.comments') }}
            </h2>
            <livewire:comments-section :model="$clip" :type="'backend'"/>
            @livewireScripts

        </div>

    @include('backend.assets.list', ['assets'=>$clip->assets])
@endsection
