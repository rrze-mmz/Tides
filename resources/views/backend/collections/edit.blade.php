@extends('layouts.backend')

@section('content')
    <div class="mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
                dark:text-white dark:border-white"
    >
        Edit Collection
    </div>

    <div class="flex px-2 py-2">
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
                <div class="col-span-7 w-4/5 space-x-4">
                    <x-button class="bg-blue-600 hover:bg-blue-700">
                        Update collection
                    </x-button>
                    <x-back-button :url="route('collections.index')" class="bg-green-600 hover:bg-green-700">
                        Back to collections index
                    </x-back-button>
                </div>
            </div>
        </form>
    </div>
    <div class="py-10 mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
                dark:text-white dark:border-white"
    >
        Collection options
    </div>
    <div class="pt-10 mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
                dark:text-white dark:border-white"
    >
        Toggle clips
    </div>
    <div class="flex pt-6">
        <form action="{{route('collections.toggleClips',$collection)}}"
              method="POST"
              class="w-4/5">
            @csrf
            <div class="flex flex-col gap-6">
                <x-form.select2-multiple field-name="ids"
                                         label="Clips"
                                         select-class="select2-tides-clips"
                                         :model="null"
                                         :items="[]"
                                         :full-col="true"
                                         :required="true"
                />
                <x-form.button :link="$link=false"
                               type="submit"
                               text="Toggle selected clips"
                />
            </div>
        </form>
    </div>
    <div class="pt-10                     mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
                dark:text-white dark:border-white"
    >
        Collection Clips
    </div>
    <x-list-clips :series="$collection" :clips="$collection->clips" dashboardAction="@can('menu-dashboard-admin')" />
@endsection
