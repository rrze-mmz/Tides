@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black text-2xl flex-col dark:text-white dark:border-white font-normal pb-2">
        {{ $episode->title }} [ ID : {{ $episode->id }}]
    </div>
    <div class="flex">
        <form action="{{ route('podcasts.update', $episode) }}"
              method="POST"
              class="w-full">
            @method('PATCH')
            @csrf
            <div class="grid grid-cols-3">
                <div class="col-span-2">
                    <div class="flex flex-col gap-3 space-y-4 pt-4">
                        <x-form.input field-name="title"
                                      input-type="text"
                                      :value="$episode->title"
                                      label="{{ __('common.forms.title') }}"
                                      :full-col="true"
                                      :required="true"
                        />

                        <x-form.textarea field-name="description"
                                         :value="$episode->description"
                                         label="{{ __('common.forms.description') }}"
                        />

                        <x-form.select2-multiple field-name="hosts"
                                                 label="Host(s)"
                                                 select-class="select2-tides-presenters"
                                                 :model="$episode"
                                                 :items="$episode->getPrimaryPresenters()"
                        />

                        <x-form.select2-multiple field-name="guests"
                                                 label="Guest(s)"
                                                 select-class="select2-tides-presenters"
                                                 :model="$episode"
                                                 :items="$episode->getPrimaryPresenters(primary: false)"
                        />

                        <x-form.toggle-button :value="$episode->is_published"
                                              label="Is public"
                                              field-name="is_published"
                        />
                        <x-form.input field-name="website_url"
                                      input-type="url"
                                      :value="$episode->website_url"
                                      label="{{ __('common.forms.website url') }}"
                                      :full-col="true"
                                      :required="false"
                        />
                        <x-form.input field-name="apple_podcasts_url"
                                      input-type="url"
                                      :value="$episode->apple_podcasts_url"
                                      label="{{ __('common.forms.apple podcasts url') }}"
                                      :full-col="true"
                                      :required="false"
                        />
                        <x-form.input field-name="spotify_url"
                                      input-type="url"
                                      :value="$episode->spotify_url"
                                      label="{{ __('common.forms.spotify url') }}"
                                      :full-col="true"
                                      :required="false"
                        />
                    </div>
                </div>
                <div class="row-span-4 pr-4">
                    <div class="flex-row">
                        <div class="text-lg  dark:text-white pt-4 ">
                            <span class="font-bold">Podcast Owner:</span>
                            <span class="italic">
                                       @if(is_null($episode->owner))
                                    Podcast has no owner yet
                                @else
                                    {{$episode->owner?->getFullNameAttribute().'-'.$episode->owner?->username}}
                                @endif

                                </span>
                        </div>
                        @can('change-series-owner')
                            <div class="w-full pt-6 dark:text-white">
                                <div class="w-full pb-6">
                                    <label>
                                        <select
                                            class="p-2 w-full select2-tides-users
                                        focus:outline-none focus:bg-white focus:border-blue-500 "
                                            name="owner_id"
                                            style="width: 100%"
                                        >
                                        </select>
                                    </label>
                                </div>
                            </div>
                        @endcan
                    </div>
                    <div class="flex w-full">
                        <img
                            @if(!is_null($episode->cover))
                                src="{{ asset('images/'.$episode->cover->file_name) }}"
                            alt="{{ $episode->cover->description }}"
                            @else
                                src="{{ asset('images/'.$podcast->cover->file_name) }}"
                            alt="{{ $podcast->cover->description }}"
                            @endif

                            class="w-full h-auto rounded-md">
                    </div>
                    @if(is_null($episode->cover))
                        <div class="text-lg dark:text-white italic">
                            *inherited from podcast
                        </div>
                    @endif
                    <div class="flex flex-col items-center place-content-center text-lg pt-8 pb-4 border-b border-black
                    dark:text-white mb-4">
                        <div class="pb-4">
                            Upload a new podcast cover
                        </div>
                        <div class="italic text-xs">
                            * please prefer a resolution of 1400x1400px
                        </div>

                    </div>

                    <input type="file"
                           name="image"
                           class="filepond"
                           data-max-file-size="10MB"
                    />

                    @error('image')
                    <div class="col-start-2 col-end-6">
                        <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                    </div>
                    @enderror
                </div>
                <div class="col-span-3 pt-10">
                    <div class="">
                        <x-button :type="'submit'" class="bg-blue-600 hover:bg-blue-700">
                            Podcast {{ __('common.actions.update') }}
                        </x-button>
                        <a href="{{route('podcasts.index')}}">
                            <x-button type="button" class="ml-3 bg-green-600 hover:bg-green-700">
                                {{__('common.actions.cancel')}}
                            </x-button>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </main>
@endsection
