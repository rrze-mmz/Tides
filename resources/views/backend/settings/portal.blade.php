@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 text-2xl font-semibold">
        Portal settings
    </div>
    <div class="flex px-2 py-2">
        <form action="{{ route('settings.portal.update') }}"
              method="POST"
              class="w-4/5">
            @csrf
            @method('PUT')
            <x-form.toggle-button :value="$setting['maintenance_mode']"
                                  label="Maintenance mode"
                                  field-name="maintenance_mode"
            />
            <x-form.toggle-button :value="$setting['allow_user_registration']"
                                  label="Allow user registration"
                                  field-name="allow_user_registration"
            />
            <x-form.input field-name="feeds_default_owner_name"
                          input-type="text"
                          :value="$setting['feeds_default_owner_name']"
                          label="Default feeds owner name"
                          :fullCol="true"
                          :required="true"/>
            <x-form.input field-name="feeds_default_owner_email"
                          input-type="email"
                          :value="$setting['feeds_default_owner_email']"
                          label="Default feeds email"
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
