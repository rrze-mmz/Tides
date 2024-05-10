@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 text-2xl font-semibold dark:text-white dark:border-white">
        Streaming settings
    </div>
    <div class="flex px-2 py-2">
        <form action="{{ route('settings.streaming.update') }}"
              method="POST"
              class="w-4/5 space-y-4">
            @csrf
            @method('PUT')
            <div class="bg-gray-200 border-2 rounded-2xl p-4 my-4 dark:bg-slate-800 dark:border-indigo-950 space-y-4">
                <div
                    class="flex border-b border-black pb-1 text-xl font-semibold text-indigo-800
                    dark:text-indigo-400 dark:border-white mb-4 ">
                    VOD Server settings
                </div>
                <x-form.input field-name="wowza_server1_engine_url"
                              input-type="url"
                              :value="old('wowza_server1_engine_url',$setting['wowza']['server1']['engine_url'])"
                              label="VOD Engine URL"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_server1_api_url"
                              input-type="url"
                              :value="old('wowza_server1_api_url',$setting['wowza']['server1']['api_url'])"
                              label="VOD Wowza API URL"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_server1_api_username"
                              input-type="text"
                              :value="old('wowza_server1_api_username',$setting['wowza']['server1']['api_username'])"
                              label="Digest username"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_server1_api_password"
                              input-type="password"
                              :value="old('wowza_server1_api_password',$setting['wowza']['server1']['api_password'])"
                              label="Digest password"
                              :fullCol="true"
                              :required="true" />
                <div
                    class="mb-5 border-b border-black py-4 pb-2 text-base  dark:text-white  font-medium dark:border-white">
                    App Settings
                </div>
                <x-form.input field-name="wowza_server1_content_path"
                              input-type="text"
                              :value="old('wowza_server1_content_path',$setting['wowza']['server1']['content_path'])"
                              label="Content path"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_server1_secure_token"
                              input-type="text"
                              :value="old('wowza_server1_secure_token',$setting['wowza']['server1']['secure_token'])"
                              label="Secure token"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_server1_token_prefix"
                              input-type="text"
                              :value="old('wowza_server1_token_prefix',$setting['wowza']['server1']['token_prefix'])"
                              label="Token prefix"
                              :fullCol="true"
                              :required="true" />
            </div>
            <div class="bg-gray-200 border-2 rounded-2xl p-4 my-4 dark:bg-slate-800 dark:border-indigo-950 space-y-4">
                <div
                    class="flex border-b border-black pb-1 text-xl font-semibold text-indigo-800
                    dark:text-indigo-400 dark:border-white mb-4 ">
                    Livestream Server settings
                </div>
                <x-form.input field-name="wowza_server2_engine_url"
                              input-type="url"
                              :value="old('wowza_server2_engine_url',$setting['wowza']['server2']['engine_url'])"
                              label="Engine URL"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_server2_api_url"
                              input-type="url"
                              :value="old('wowza_server2_api_url',$setting['wowza']['server2']['api_url'])"
                              label="Wowza API URL"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_server2_api_username"
                              input-type="text"
                              :value="old('wowza_server2_api_username',$setting['wowza']['server2']['api_username'])"
                              label="Digest username"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_server2_api_password"
                              input-type="password"
                              :value="old('wowza_server2_api_password',$setting['wowza']['server2']['api_password'])"
                              label="Digest password"
                              :fullCol="true"
                              :required="true" />
                <div
                    class="mb-5 border-b border-black py-4 pb-2 text-base  dark:text-white  font-medium dark:border-white">
                    App Settings
                </div>
                <x-form.input field-name="wowza_server2_content_path"
                              input-type="text"
                              :value="old('wowza_server2_content_path',$setting['wowza']['server2']['content_path'])"
                              label="Content path"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_server2_secure_token"
                              input-type="text"
                              :value="old('wowza_server2_secure_token',$setting['wowza']['server2']['secure_token'])"
                              label="Secure token"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_server2_token_prefix"
                              input-type="text"
                              :value="old('wowza_server2_token_prefix',$setting['wowza']['server2']['token_prefix'])"
                              label="Token prefix"
                              :fullCol="true"
                              :required="true" />
            </div>

            <div class="bg-gray-200 border-2 rounded-2xl p-4 my-4 dark:bg-slate-800 dark:border-indigo-950 space-y-4">
                <div
                    class="flex border-b border-black pb-1 text-xl font-semibold text-indigo-800
                    dark:text-indigo-400 dark:border-white mb-4 ">
                    CDN Server settings
                </div>
                <x-form.input field-name="cdn_server1_url"
                              input-type="text"
                              :value="old('cdn_server1_url',$setting['cdn']['server1']['url'])"
                              label="CDN Server URL"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="cdn_server1_secret"
                              input-type="text"
                              :value="old('cdn_server1_secret',$setting['cdn']['server1']['secret'])"
                              label="CDN Secret hash"
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
