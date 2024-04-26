@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 text-2xl font-semibold dark:text-white dark:border-white">
        Search settings
    </div>
    <div class="flex p-2">
        <form action="{{ route('settings.openSearch.update') }}"
              method="POST"
              class="w-4/5 space-y-4">
            @csrf
            @method('PUT')
            <div class="bg-gray-200 border-2 rounded-2xl p-4 my-4 dark:bg-slate-800 dark:border-indigo-950 space-y-4">
                <div
                    class="flex border-b border-black pb-1 text-xl font-semibold text-indigo-800
                    dark:text-indigo-400 dark:border-white mb-4 ">
                    General Settings
                </div>
                <x-form.toggle-button :value="$setting['search_frontend_enable_open_search']"
                                      label="Enable frontend opensearch"
                                      field-name="search_frontend_enable_open_search"
                />
            </div>
            <div class="bg-gray-200 border-2 rounded-2xl p-4 my-4 dark:bg-slate-800 dark:border-indigo-950 space-y-4">
                <div
                    class="flex border-b border-black pb-1 text-xl font-semibold text-indigo-800
                    dark:text-indigo-400 dark:border-white mb-4 ">
                    OpenSearch Settings
                </div>
                <x-form.input field-name="url"
                              input-type="text"
                              :value="$setting['url']"
                              label="Admin URL"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="port"
                              input-type="number"
                              :value="$setting['port']"
                              label="Port"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="username"
                              input-type="text"
                              :value="$setting['username']"
                              label="Admin username"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="password"
                              input-type="password"
                              :value="$setting['password']"
                              label="Admin password"
                              :fullCol="true"
                              :required="true" />
                <div
                    class="mb-5 border-b border-black py-4 pb-2 text-base font-medium  dark:text-white dark:border-white">
                    Index Settings
                </div>
                <x-form.input field-name="prefix"
                              input-type="text"
                              :value="$setting['prefix']"
                              label="Index prefix"
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
