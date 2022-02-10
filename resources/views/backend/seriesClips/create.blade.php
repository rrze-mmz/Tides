@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black text-2xl">
        Add clip to {{ $series->title }}
    </div>
    <div class="flex py-4 px-2 ">
        <form action="{{route('series.clip.store', $series)}}"
              method="POST"
              class="w-4/5">
            @csrf

            <div class="flex flex-col gap-3">

                <x-form.input field-name="episode"
                              input-type="number"
                              :value="$series->clips()->count()+1"
                              label="Episode"
                              :full-col="false"
                              :required="false"
                />

                <x-form.datepicker field-name="recording_date"
                                   label="Recording Date"
                                   :full-col="false"
                                   :value="now()"/>


                <x-form.input field-name="title"
                              input-type="text"
                              :value="old('title')"
                              label="Title"
                              :full-col="true"
                              :required="true"
                />

                <x-form.textarea field-name="description"
                                 :value="old('description')"
                                 label="Description"
                />

                <x-form.select2-single field-name="organization_id"
                                       label="Organization"
                                       select-class="select2-tides-organization"
                                       model="organization"
                                       :selectedItem="1"
                />

                <x-form.select2-single field-name="language_id"
                                       label="Language"
                                       select-class="select2-tides"
                                       model="language"
                                       :selectedItem="1"
                />

                <div class="border-solid   border-b-black border-b mb-2 pb-2 font-bold text-left text-xl">
                    Metadata
                </div>

                <x-form.select2-single field-name="context_id"
                                       label="Context"
                                       select-class="select2-tides"
                                       model="context"
                                       :selectedItem="1"
                />

                <x-form.select2-single field-name="format_id"
                                       label="Format"
                                       select-class="select2-tides"
                                       model="format"
                                       :selectedItem="1"
                />

                <x-form.select2-single field-name="type_id"
                                       label="Type"
                                       select-class="select2-tides"
                                       model="type"
                                       :selectedItem="1"
                />

                <x-form.select2-multiple field-name="presenters"
                                         label="Presenters"
                                         select-class="select2-tides-presenters"
                                         :model="null"
                                         :items="[]"
                />

                <x-form.select2-single field-name="semester_id"
                                       label="Semester"
                                       select-class="select2-tides"
                                       model="semester"
                                       :selectedItem="$series->latestClip?->semeseter_id"
                />

                <x-form.select2-multiple field-name="tags"
                                         label="Tags"
                                         select-class="select2-tides-tags"
                                         :model="null"
                                         :items="[]"
                />

                <div class="border-solid   border-b-black border-b mb-2 pb-2 font-bold text-left text-xl">
                    Access
                </div>

                <x-form.select2-multiple field-name="acls"
                                         label="Accessible via"
                                         :model="null"
                                         select-class="select2-tides"
                />

                <x-form.password field-name="password"
                                 :value="$series->password"
                                 label="Password"
                                 :full-col="true"
                />

                <x-form.toggle-button :value="true"
                                      label="Allow comments"
                                      field-name="allow_comments"
                />

                <x-form.toggle-button :value="$series->is_public"
                                      label="Public available"
                                      field-name="is_public"
                />

            </div>
            <div class="pt-10 ">
                <x-form.button :link="$link=false" type="submit" text="Add a Clip to Series"/>
            </div>
        </form>
    </div>
    </main>
@endsection
