@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 text-2xl font-semibold dark:text-white dark:border-white">
        Opencast settings
    </div>
    <div class="flex px-2 py-2">
        <form action="{{ route('settings.opencast.update') }}"
              method="POST"
              class="w-4/5 space-y-4">
            @csrf
            @method('PUT')

            <x-form.input field-name="url"
                          input-type="url"
                          :value="$setting['url']"
                          label="Admin URL"
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
            <x-form.input field-name="default_workflow_id"
                          input-type="text"
                          :value="$setting['default_workflow_id']"
                          label="Default workflow ID"
                          :fullCol="true"
                          :required="true" />
            <x-form.input field-name="upload_workflow_id"
                          input-type="text"
                          :value="$setting['upload_workflow_id']"
                          label="Upload workflow ID"
                          :fullCol="true"
                          :required="true" />
            <x-form.input field-name="theme_id_top_right"
                          input-type="number"
                          :value="$setting['theme_id_top_right']"
                          label="Theme ID top right"
                          :fullCol="false"
                          :required="true" />
            <x-form.input field-name="theme_id_top_left"
                          input-type="number"
                          :value="$setting['theme_id_top_left']"
                          label="Theme ID top left"
                          :fullCol="false"
                          :required="true" />
            <x-form.input field-name="theme_id_bottom_right"
                          input-type="number"
                          :value="$setting['theme_id_bottom_right']"
                          label="Theme ID bottom right"
                          :fullCol="false"
                          :required="true" />
            <x-form.input field-name="theme_id_bottom_left"
                          input-type="number"
                          :value="$setting['theme_id_bottom_left']"
                          label="Theme ID top right"
                          :fullCol="false"
                          :required="true" />
            <div class="mb-5 border-b border-black py-4 pb-2 text-xl  dark:text-white dark:border-white">
                Archive Settings
            </div>
            <x-form.input field-name="archive_path"
                          input-type="text"
                          :value="$setting['archive_path']"
                          label="Archive path"
                          :fullCol="true"
                          :required="true" />
            <x-form.input field-name="assistant_group_name"
                          input-type="text"
                          :value="$setting['assistant_group_name']"
                          label="Opencast assistants group name"
                          :fullCol="true"
                          :required="true" />
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
