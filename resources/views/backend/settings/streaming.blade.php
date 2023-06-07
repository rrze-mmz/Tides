@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 text-2xl font-semibold">
        Streaming settings
    </div>
    <div class="flex px-2 py-2">
        <form action="{{ route('settings.streaming.update') }}"
              method="POST"
              class="w-4/5">
            @csrf
            @method('PUT')

            <x-form.input field-name="engine_url"
                          input-type="url"
                          :value="$setting['engine_url']"
                          label="Wowza Engine URL"
                          :fullCol="true"
                          :required="true"/>
            <x-form.input field-name="api_url"
                          input-type="url"
                          :value="$setting['api_url']"
                          label="Wowza API URL"
                          :fullCol="true"
                          :required="true"/>
            <x-form.input field-name="username"
                          input-type="text"
                          :value="$setting['username']"
                          label="Digest username"
                          :fullCol="true"
                          :required="true"/>
            <x-form.input field-name="password"
                          input-type="password"
                          :value="$setting['password']"
                          label="Digest password"
                          :fullCol="true"
                          :required="true"/>
            <div class="mb-5 border-b-2 border-black py-4 pb-2 text-xl">
                App Settings
            </div>
            <x-form.input field-name="content_path"
                          input-type="text"
                          :value="$setting['content_path']"
                          label="Content path"
                          :fullCol="true"
                          :required="true"/>
            <x-form.input field-name="secure_token"
                          input-type="text"
                          :value="$setting['secure_token']"
                          label="Secure token"
                          :fullCol="true"
                          :required="true"/>
            <x-form.input field-name="token_prefix"
                          input-type="text"
                          :value="$setting['token_prefix']"
                          label="Token prefix"
                          :fullCol="true"
                          :required="true"/>
            <div class="mt-10">
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
