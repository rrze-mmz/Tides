@extends('layouts.backend')

@section('content')
        <div class="flex pb-2 font-semibold border-b border-black font-2xl">
            Add clip to {{ $series->title }}
        </div>
        <div class="flex py-2 px-2">
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

                    <x-form.select2-multiple field-name="tags"
                                             label="Tags"
                                             select-class="select2-tags-multiple"
                                             :items="[]"
                    />

                    <x-form.select2-multiple field-name="acls"
                                             label="Accessible via"
                                             select-class="js-select2-tides-multiple"
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

                </div>
                <div class="pt-10 ">
                    <x-form.button :link="$link=false" type="submit" text="Add a Clip to Series"/>
                </div>
            </form>
        </div>
    </main>
@endsection
