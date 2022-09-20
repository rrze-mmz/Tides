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
            <x-form.button :link="$link=false"
                           type="submit"
                           text="Update"/>
        </form>
    </div>
@endsection
