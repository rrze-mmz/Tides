@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black text-2xl">
        Elasticsearch settings
    </div>
    <div class="flex p-2 ">
        <form action="{{ route('settings.elasticSearch.update') }}"
              method="POST"
              class="w-4/5 ">
            @csrf
            @method('PUT')
            <x-form.input field-name="url"
                          input-type="text"
                          :value="$setting['url']"
                          label="Admin URL"
                          :fullCol="true"
                          :required="true"/>
            <x-form.input field-name="port"
                          input-type="number"
                          :value="$setting['port']"
                          label="Port"
                          :fullCol="true"
                          :required="true"/>
            <x-form.input field-name="username"
                          input-type="text"
                          :value="$setting['username']"
                          label="Admin username"
                          :fullCol="true"
                          :required="true"/>
            <x-form.input field-name="password"
                          input-type="password"
                          :value="$setting['password']"
                          label="Admin password"
                          :fullCol="true"
                          :required="true"/>
            <div class="py-4 pb-2 border-b-2 border-black text-xl mb-5">
                Index Settings
            </div>
            <x-form.input field-name="prefix"
                          input-type="text"
                          :value="$setting['prefix']"
                          label="Index prefix"
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
