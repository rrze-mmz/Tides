@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black text-2xl flex-col dark:text-white dark:border-white font-normal pb-2">
        <div class="font-semibold">
            Mass update clip metadata for series: <span class="pl-2 font-semibold">{{ $series->title }}</span>
        </div>
    </div>
    <div class="flex px-2 py-2">
        <form action="{{ route('series.clips.batch.update.clips.metadata', $series) }}"
              method="POST"
              class="w-4/5"
        >
            @csrf
            @method('PATCH')
            <div class="flex flex-col gap-3">
                @php $clip = $series->clips()->orderBy('episode')->first() @endphp
                <x-form.input field-name="title"
                              input-type="text"
                              :value="old('title', $clip->title)"
                              label="Title"
                              :fullCol="true"
                              :required="true" />
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
                <div class="mb-2 border-b border-solid border-b-black pb-2 text-left text-xl font-bold
                            dark:text-white dark:border-white "
                >
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

            </div>

            <div class="flex space-x-4 pt-10">
                <x-button type="submit" class="bg-blue-600 hover:bg-blue-700">
                    Mass update all clips
                </x-button>
                <x-back-button :url="route('series.edit', $series).'#actions'" class="bg-green-600 hover:bg-green-700">
                    Back to edit series
                </x-back-button>
            </div>
        </form>
    </div>

    </div>
    <x-list-clips :series="$series" :clips="$clips" dashboardAction="@can('edit-series', $series)"
                  :reorder="false" />
@endsection