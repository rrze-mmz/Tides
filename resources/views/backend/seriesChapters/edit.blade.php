@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl ">
        <p>
            Course <span class="italic">{{ $series->title }}</span> edit chapter
        </p>
    </div>

    <div class="flex pt-5">
        <form action=""
              class="pt-6 w-4/5">
            <div class="flex flex-col gap-3">
                <x-form.input field-name="position"
                              input-type="number"
                              :value="$chapter->position"
                              label="Episode"
                              :full-col="false"
                              :required="true"/>
                <x-form.input field-name="title"
                              input-type="text"
                              :value="$chapter->title"
                              label="Title"
                              :full-col="true"
                              :required="true"/>
                <x-form.toggle-button :value="$chapter->default"
                                      label="Default chapter"
                                      field-name="default"
                />
                <div class="pt-6">
                    <x-form.button :link="$link=false"
                                   type="submit"
                                   text="Chapter Update"/>
                    <x-form.button :link="route('series.chapters.index',$series)"
                                   color="green"
                                   type="back"
                                   text="Back to Series chapters"
                    />
                </div>

            </div>
        </form>

        <div class="space-y-5 w-1/5 h-full">
            <div class="w-full py-4 px-4 mx-4 h-full rounded ">


            </div>
        </div>

    </div>
    <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
        More actions
    </div>
    <div class="flex items-center pt-3 space-x-6">
        <form action="{{route('series.chapters.delete',[$series, $chapter])}}"
              method="POST">
            @method('DELETE')
            @csrf
            <x-form.button :link="$link=false"
                           type="submit"
                           color="red"
                           text="Chapter Delete"/>
        </form>
    </div>
    @include('backend.seriesChapters.edit._add-clips-to-chapter')
    @include('backend.seriesChapters.edit._list-clips-for-chapter')

@endsection
