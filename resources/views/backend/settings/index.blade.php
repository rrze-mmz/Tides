@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Settings index
    </div>
    <div class="grid grid-cols-3 gap-4 pt-4">
        <div class="m-2 p-2 border-black border-solid rounded-lg border-2">
            <div class="flex flex-col justify-between place-content-around">
                <div>
                    <h3 class="pb-6 font-semibold font-light">Portal
                    </h3>
                </div>
                <div>
                    <x-form.button :link="route('settings.portal.show')" type="submit" text="Settings"/>
                </div>
            </div>
        </div>
        <div class="m-2 p-2 border-black border-solid rounded-lg border-2">
            <div class="flex flex-col justify-between place-content-around">
                <div>
                    <h3 class="pb-6 font-semibold font-light">Opencast
                    </h3>
                </div>
                <div>
                    <x-form.button :link="route('settings.opencast.show')" type="submit"
                                   text="Go to settings"/>
                </div>
            </div>
        </div>
    </div>
@endsection
