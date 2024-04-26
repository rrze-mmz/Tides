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
                <x-form.input field-name="wowza_vod_engine_url"
                              input-type="url"
                              :value="old('wowza_vod_engine_url',$setting['wowza_vod_engine_url'])"
                              label="VOD Engine URL"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_vod_api_url"
                              input-type="url"
                              :value="old('wowza_vod_api_url',$setting['wowza_vod_api_url'])"
                              label="VOD Wowza API URL"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_vod_username"
                              input-type="text"
                              :value="old('wowza_vod_username',$setting['wowza_vod_username'])"
                              label="Digest username"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_vod_password"
                              input-type="password"
                              :value="old('wowza_vod_password',$setting['wowza_vod_password'])"
                              label="Digest password"
                              :fullCol="true"
                              :required="true" />
                <div
                    class="mb-5 border-b border-black py-4 pb-2 text-base  dark:text-white  font-medium dark:border-white">
                    App Settings
                </div>
                <x-form.input field-name="wowza_vod_content_path"
                              input-type="text"
                              :value="old('wowza_vod_content_path',$setting['wowza_vod_content_path'])"
                              label="Content path"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_vod_secure_token"
                              input-type="text"
                              :value="old('wowza_vod_secure_token',$setting['wowza_vod_secure_token'])"
                              label="Secure token"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_vod_token_prefix"
                              input-type="text"
                              :value="old('wowza_vod_token_prefix',$setting['wowza_vod_token_prefix'])"
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
                <x-form.input field-name="wowza_livestream_engine_url"
                              input-type="url"
                              :value="old('wowza_livestream_engine_url',$setting['wowza_livestream_engine_url'])"
                              label="Engine URL"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_livestream_api_url"
                              input-type="url"
                              :value="old('wowza_livestream_api_url',$setting['wowza_livestream_api_url'])"
                              label="Wowza API URL"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_livestream_username"
                              input-type="text"
                              :value="old('wowza_livestream_username',$setting['wowza_livestream_username'])"
                              label="Digest username"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_livestream_password"
                              input-type="password"
                              :value="old('wowza_livestream_password',$setting['wowza_livestream_password'])"
                              label="Digest password"
                              :fullCol="true"
                              :required="true" />
                <div
                    class="mb-5 border-b border-black py-4 pb-2 text-base  dark:text-white  font-medium dark:border-white">
                    App Settings
                </div>
                <x-form.input field-name="wowza_livestream_content_path"
                              input-type="text"
                              :value="old('wowza_livestream_content_path',$setting['wowza_livestream_content_path'])"
                              label="Content path"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_livestream_secure_token"
                              input-type="text"
                              :value="old('wowza_livestream_secure_token',$setting['wowza_livestream_secure_token'])"
                              label="Secure token"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="wowza_livestream_token_prefix"
                              input-type="text"
                              :value="old('wowza_livestream_token_prefix',$setting['wowza_livestream_token_prefix'])"
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
                <x-form.input field-name="cdn_server_url"
                              input-type="text"
                              :value="old('cdn_server_url',$setting['cdn_server_url'])"
                              label="CDN Server URL"
                              :fullCol="true"
                              :required="true" />
                <x-form.input field-name="cdn_server_secret"
                              input-type="text"
                              :value="old('cdn_server_secret',$setting['cdn_server_secret'])"
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
