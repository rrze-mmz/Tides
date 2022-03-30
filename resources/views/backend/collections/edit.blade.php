@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Edit Collection
    </div>

    <div class="flex py-2 px-2">
        <form action="{{ route('collections.update',$collection) }}"
              method="POST"
              class="w-4/5">
            @csrf
            @method('PATCH')
            <div class="flex flex-col gap-6">

                <x-form.input field-name="position"
                              input-type="number"
                              :value="$collection->position"
                              label="Position"
                              :full-col="false"
                              :required="false"
                />

                <x-form.input field-name="title"
                              input-type="text"
                              :value="$collection->title"
                              label="Title"
                              :full-col="true"
                              :required="true"
                />

                <x-form.textarea field-name="description"
                                 :value="$collection->description"
                                 label="Description"
                />

                <x-form.toggle-button :value="$collection->is_public"
                                      label="Public available"
                                      field-name="is_public"
                />
                <div class="col-span-7 w-4/5">
                    <x-form.button :link="$link=false"
                                   type="submit"
                                   text="Update collection"
                    />

                    <x-form.button :link="route('collections.index')"
                                   type="back"
                                   text="Back to collections index"
                                   color="green"
                    />
                </div>
            </div>
        </form>
    </div>
@endsection
