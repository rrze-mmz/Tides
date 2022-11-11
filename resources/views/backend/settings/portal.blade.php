@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black text-2xl">
        Portal settings
    </div>
    <div class="flex py-2 px-2">
        <form action="{{ route('settings.portal.update') }}"
              method="POST"
              class="w-4/5 ">
            @csrf
            @method('PUT')
            <x-form.toggle-button :value="$setting['maintenance_mode']"
                                  label="Maintenance mode"
                                  field-name="maintenance_mode"
            />
            <div class="mt-10">
                <x-button class="bg-blue-600 hover:bg-blue-700">
                    Update
                </x-button>
                <a href="{{ route('settings.portal.index') }}">
                    <x-button type="button" class="bg-gray-600 hover:bg-gray-700">
                        Cancel
                    </x-button>
                </a>
            </div>

        </form>
    </div>
@endsection
