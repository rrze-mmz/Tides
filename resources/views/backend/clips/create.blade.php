@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Creates new clip
    </div>
    <div class="flex py-2 px-2">
        <form action="{{ route('clips.store')}}"
              method="POST"
              class="w-4/5">
            @csrf

            <div class="flex flex-col gap-3">

                <x-form.input field-name="episode"
                              input-type="number"
                              value="1"
                              label="Episode"
                              :full-col="false"
                              :required="false"
                />

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
                                       :selectedItem="(old('organization_id'))?? 1 "
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
                                       :selectedItem="old('semester_id')"
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
                                 :value="old('password')"
                                 label="Password"
                                 :full-col="true"
                />

                <x-form.toggle-button :value="true"
                                      label="Allow comments"
                                      field-name="allow_comments"
                />

                <x-form.toggle-button :value="true"
                                      label="Public available"
                                      field-name="isPublic"
                />

                <div class="col-span-7 w-4/5">
                    <x-form.button :link="$link=false" type="submit" text="Create clip"/>
                </div>
            </div>
        </form>
    </div>
    </main>
@endsection
