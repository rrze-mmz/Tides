@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Create new series
    </div>
    <div class="flex py-2 px-2">
        <form action="/admin/series/"
              method="POST"
              class="w-4/5">
            @csrf

            <div class="flex flex-col gap-3">

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

                <x-form.select2-multiple field-name="presenters"
                                         label="Presenters"
                                         select-class="select2-tides-presenters"
                                         :model="null"
                                         :items="[]"
                />

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
                                      label="Public available"
                                      field-name="isPublic"
                />

                <div class="flex content-center items-center mb-6">
                </div>
                <div class="col-span-7 w-4/5">
                    <x-form.button :link="$link=false" type="submit" text="Create series"/>
                </div>
            </div>

        </form>
    </div>
    </main>
@endsection
