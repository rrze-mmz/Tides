@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black text-2xl">
        Streaming settings
    </div>
    <div class="flex py-2 px-2">
        <form action="{{ route('settings.streaming.update') }}"
              method="POST"
              class="w-4/5 ">
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
            <div class="py-4 pb-2 border-b-2 border-black text-xl mb-5">
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
            <x-form.button :link="$link=false"
                           type="submit"
                           text="Update"/>
        </form>
    </div>
@endsection
