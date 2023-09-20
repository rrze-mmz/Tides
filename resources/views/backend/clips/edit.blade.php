@php use App\Enums\Acl; use Carbon\Carbon; @endphp
@extends('layouts.backend')

@section('content')
    <div class="w-full flex-row border-b border-black pb-2 font-2xl">
        <div class="flex w-full items-center justify-between">
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
        <div class="flex pt-2 text-sm font-light italic">
            <span
                class="pl-2"> created
                @if(!is_null($clip->owner_id))
                    by {{ $clip->owner->getFullNameAttribute() }} ({{ $clip->owner->username }})
                @endif
                at {{$clip->created_at}}</span>
        </div>
    </div>
    <div class="flex px-2 py-2">
        <form action="{{ route('clips.update', $clip) }}"
              method="POST"
              class="w-4/5"
        >
            @csrf
            @method('PATCH')

            <div class="flex flex-col gap-3">

                <x-form.input field-name="episode"
                              input-type="number"
                              :value="old('episode', $clip->episode)"
                              label="Episode"
                              :full-col="false"
                              :required="true" />

                <x-form.datepicker field-name="recording_date"
                                   label="Recording Date"
                                   :full-col="false"
                                   :value="old('recording_date', $clip->recording_date)" />

                <x-form.input field-name="title"
                              input-type="text"
                              :value="old('title', $clip->title)"
                              label="Title"
                              :fullCol="true"
                              :required="true" />

                @if($clip->series->chapters()->count() > 0)
                    <x-form.select2-single field-name="chapter_id"
                                           label="Chapter"
                                           select-class="select2-tides"
                                           model="chapter"
                                           :where-i-d="old('series_id', $clip->series->id)"
                                           :selectedItem="old('chapter_id', $clip->chapter_id)"
                    />
                @endif
                <x-form.textarea field-name="description"
                                 :value="old('description', $clip->description)"
                                 label="Description" />

                <x-form.select2-single field-name="organization_id"
                                       label="Organization"
                                       select-class="select2-tides-organization"
                                       model="organization"
                                       :selectedItem="old('organization_id', $clip->organization_id)"
                />

                <x-form.select2-single field-name="language_id"
                                       label="Language"
                                       select-class="select2-tides"
                                       model="language"
                                       :selectedItem="old('language_id', $clip->lanugage_id)"
                />

                <div class="mb-2 border-b border-solid border-b-black pb-2 text-left text-xl font-bold">
                    Metadata
                </div>

                <x-form.select2-single field-name="context_id"
                                       label="Context"
                                       select-class="select2-tides"
                                       model="context"
                                       :selectedItem="old('context_id', $clip->context_id)"
                />
                <x-form.select2-single field-name="format_id"
                                       label="Format"
                                       select-class="select2-tides"
                                       model="format"
                                       :selectedItem="old('format_id', $clip->format_id)"
                />
                <x-form.select2-single field-name="type_id"
                                       label="Type"
                                       select-class="select2-tides"
                                       model="type"
                                       :selectedItem="old('clip_id', $clip->type_id)"
                />

                <x-form.select2-single field-name="semester_id"
                                       label="Semester"
                                       select-class="select2-tides"
                                       model="semester"
                                       :selectedItem="old('semester_id', $clip->semester_id)"
                />

                <x-form.select2-multiple field-name="presenters"
                                         :model="$clip"
                                         label="Presenters"
                                         select-class="select2-tides"
                                         :items="$clip->presenters" />

                <x-form.select2-multiple field-name="tags"
                                         :model="$clip"
                                         label="Tags"
                                         select-class="select2-tides-tags"
                                         :items="$clip->tags" />

                <x-form.select2-multiple field-name="acls"
                                         :model="$clip"
                                         label="Accessible via"
                                         select-class="select2-tides" />

                <x-form.password field-name="password"
                                 :value="old('password', $clip->password)"
                                 label="Password"
                                 :full-col="true"
                />

                <x-form.toggle-button :value="old('allow_comments', $clip->allow_comments)"
                                      label="Allow comments"
                                      field-name="allow_comments"
                />

                <x-form.toggle-button :value="old('is_public', $clip->is_public)"
                                      label="Public available"
                                      field-name="is_public"
                />

                <x-form.toggle-button :value="old('is_livestream', $clip->is_livestream)"
                                      label="Livestream clip"
                                      field-name="is_livestream"
                />

                <x-date-time-picker
                    :has-time-availability="old('has_time_availability', $clip->has_time_availability)"
                    :time-availability-start="old('time_availability_start', $clip->time_availability_start)"
                    :time-availability-end="old('time_availability_end', $clip->time_availability_end)"
                    name="time_availability"
                    label="Time availability">
                </x-date-time-picker>

            </div>

            <div class="pt-10">
                <x-button type="submit" class="bg-blue-600 hover:bg-blue-700">
                    Save
                </x-button>
            </div>
        </form>

        <div class="h-full w-1/5 pr-4 space-y-5">
            @if(! is_null($clip->series_id) )
                @include('backend.clips.sidebar._series-options')
            @else
                @include('backend.clips.sidebar._assign-series')
            @endif

            @if(!$clip->is_livestream)
                @include('backend.clips.sidebar._upload-video')
            @endif

            @if ($opencastConnectionCollection['status']==='pass' && !$clip->is_livestream)
                )
                @include('backend.clips.sidebar._ingest-video')
            @endif

            @if(auth()->user()->isAdmin() && $clip->acls->pluck('id')->contains(Acl::LMS()))
                @include('backend.clips.sidebar._lms_test_link')
            @endif

            @include('backend.documents.upload',['resource'=> $clip ])
            @include('backend.images._card',['model'=> $clip, 'type'=> 'clip' ])
        </div>
    </div>

    <div class="flex border-b border-black pt-8 pb-2 font-semibold font-2xl">
        More actions
    </div>
    <div class="flex items-center pt-3 space-x-6">
        <a href="{{route('frontend.clips.show', $clip)}}">
            <x-button type='button' class="bg-blue-600 hover:bg-blue-700">
                Go to public page
            </x-button>
        </a>

        @if ($clip->assets()->count())
            <a href="{{route('admin.clips.triggerSmilFiles', $clip)}}">
                <x-button type='button' class="bg-blue-600 hover:bg-blue-700">
                    Trigger smil files
                </x-button>
            </a>
        @endif

        @if(!$clip->is_livestream)
            <a href="{{route('admin.clips.dropzone.listFiles', $clip)}}">
                <x-button type='button' class="bg-blue-600 hover:bg-blue-700">
                    Transfer files from drop zone
                </x-button>
            </a>
        @endif

        @if($opencastConnectionCollection['status']==='pass' && !$clip->is_livestream)
            <a href="{{route('admin.clips.opencast.listEvents', $clip)}}">
                <x-button type='button' class="bg-blue-600 hover:bg-blue-700">
                    Transfer files from Opencast
                </x-button>
            </a>
        @endif

        <form action="{{ route('clips.destroy',$clip) }}"
              method="POST"
        >
            @csrf
            @method('DELETE')
            <x-button type='submit' class="bg-red-600 hover:bg-red-700">
                Delete Clip
            </x-button>
        </form>
    </div>

    <div x-show="activeTab === 4" id="comments">
        <div class="flex flex-col pt-10">
            <h2 class="border-b-2 border-black pb-2 text-2xl font-semibold">
                Backend {{ __('clip.frontend.comments') }}
            </h2>
            <livewire:comments-section :model="$clip" :type="'backend'" />

        </div>

    @include('backend.assets.list', ['assets'=>$clip->assets])
@endsection
