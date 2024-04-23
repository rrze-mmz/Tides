@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 text-2xl font-semibold dark:text-white dark:border-white">
        Portal settings
    </div>
    <div class="flex px-2 py-2">
        <form action="{{ route('settings.portal.update') }}"
              method="POST"
              class="w-4/5 space-y-4">
            @csrf
            @method('PUT')
            <div class="bg-gray-200 border-2 rounded-2xl p-4 my-4 dark:bg-slate-800 dark:border-indigo-950">
                <div
                    class="flex border-b border-black pb-1 text-xl font-semibold text-indigo-800
                    dark:text-indigo-400 dark:border-white mb-4 ">
                    General settings
                </div>
                <div class="space-y-2">
                    <x-form.toggle-button :value="$setting['maintenance_mode']"
                                          label="Maintenance mode"
                                          field-name="maintenance_mode"
                    />
                    <x-form.toggle-button :value="$setting['allow_user_registration']"
                                          label="Allow user registration"
                                          field-name="allow_user_registration"
                    />
                    <x-form.toggle-button :value="$setting['show_dropbox_files_in_dashboard']"
                                          label="Show dropbox files in dashboard"
                                          field-name="show_dropbox_files_in_dashboard"
                    />
                </div>

                <div class="py-4">
                    <x-form.input field-name="feeds_default_owner_name"
                                  input-type="text"
                                  :value="$setting['feeds_default_owner_name']"
                                  label="Default feeds owner name"
                                  :fullCol="true"
                                  :required="true" />
                </div>

                <x-form.input field-name="feeds_default_owner_email"
                              input-type="email"
                              :value="$setting['feeds_default_owner_email']"
                              label="Default feeds email"
                              :fullCol="true"
                              :required="true" />
            </div>

            <div class="bg-gray-200 border-2 rounded-2xl p-4 my-4 dark:bg-slate-800 dark:border-indigo-950">
                <div
                    class="flex border-b border-black pb-1 text-xl font-semibold text-indigo-800
                    dark:text-indigo-400 dark:border-white mb-4 ">
                    Player settings
                </div>
                <x-form.toggle-button :value="$setting['player_show_article_link_in_player']"
                                      label="Show article link and text in player"
                                      field-name="player_show_article_link_in_player"
                />
                <div class="py-4">
                    <x-form.input field-name="player_article_link_url"
                                  input-type="url"
                                  :value="$setting['player_article_link_url']"
                                  label="Link article URL"
                                  :fullCol="true"
                                  :required="true" />
                </div>

                <x-form.input field-name="player_article_link_text"
                              input-type="text"
                              :value="$setting['player_article_link_text']"
                              label="Player Text for link"
                              :fullCol="true"
                              :required="true" />
            </div>
            <div class="bg-gray-200 border-2 rounded-2xl p-4 my-4 dark:bg-slate-800 dark:border-indigo-950">
                <div
                    class="flex border-b border-black pb-1 text-xl font-semibold text-indigo-800
                    dark:text-indigo-400 dark:border-white mb-4 ">
                    Clip settings
                </div>
                <x-form.input field-name="clip_generic_poster_image_name"
                              input-type="text"
                              :value="$setting['clip_generic_poster_image_name']"
                              label="Poster image file name"
                              :fullCol="true"
                              :required="true" />
            </div>

            <div class="mt-10 space-x-4 pt-10">
                <x-button class="bg-blue-600 hover:bg-blue-700">
                    Update
                </x-button>
                <a href="{{ route('systems.status') }}">
                    <x-button type="button" class="bg-gray-600 hover:bg-gray-700">
                        Cancel
                    </x-button>
                </a>
            </div>

        </form>
    </div>
@endsection
