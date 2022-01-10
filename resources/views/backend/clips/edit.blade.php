@extends('layouts.backend')

@section('content')
    <div class="flex items center  w-full pb-2 font-semibold border-b border-black font-2xl">
        <div class="flex justify-between items-end w-full">
            <div class="">
                Edit1 {{ $clip->title }} [ ID: {{ $clip->id }} ]
                <span class="pl-2 italic font-sm"> created at {{$clip->created_at}}</span>
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
    </div>
    <div class="flex py-2 px-2">
        <form action="{{ $clip->adminPath() }}"
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

                <x-form.select2-single field-name="semester_id"
                                       label="Semester"
                                       select-class="select2-tides"
                                       model="semester"
                                       :selectedItem="$clip->semester_id"
                />

                <x-form.select2-multiple field-name="presenters"
                                         :model="$clip"
                                         label="Presenters"
                                         select-class="select2-tides-presenters"
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

                <x-form.toggle-button :value="$clip->isPublic"
                                      label="Public available"
                                      field-name="isPublic"
                />

            </div>

            <x-form.button :link="$link=false" type="submit" text="Save"/>
        </form>

        <div class="space-y-5 w-1/5 h-full">
            @if(! is_null($clip->series_id) )
                @include('backend.clips.sidebar._series-options')
            @endif

            @include('backend.clips.sidebar._upload-video')

            @if ($opencastConnectionCollection = $opencastConnectionCollection->isNotEmpty())
                @include('backend.clips.sidebar._ingest-video')
            @endif

            @if(auth()->user()->isAdmin() && $clip->acls->pluck('id')->contains('2'))
                <div class="w-full py-4 px-4 mx-4 h-full bg-white rounded border">
                    <header class="items-center pb-2 mb-2 font-semibold text-center border-b">
                        LMS Test Link
                    </header>
                    <p>
                        <a
                            href="{{generateLMSToken($clip, dechex(time()), true)}}"
                            type="button"
                            class="mt-2 py-2 px-8 text-white bg-green-500 rounded shadow hover:bg-green-600
                            focus:shadow-outline focus:outline-none"
                        >LMS Test Link</a>
                    </p>
                </div>
            @endif

        </div>
    </div>

    <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
        More actions
    </div>
    <div class="flex items-center pt-3 space-x-6">
        <x-form.button :link="$clip->path()"
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

        <form action="{{ $clip->adminPath() }}"
              method="POST"
        >
            @csrf
            @method('DELETE')

            <x-form.button :link="$link=false"
                           type="delete"
                           text="Delete"
            />

        </form>

        @if($opencastConnectionCollection)
            <x-form.button :link="route('admin.clips.opencast.listEvents', $clip)"
                           type="submit"
                           text=" Transfer files from Opencast"
            />
        @endif

    </div>

    @include('backend.assets.list', ['assets'=>$clip->assets])
@endsection
