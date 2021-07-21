@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Create new user
    </div>

    <div class="flex py-2 px-2">
        <form action="/admin/users/"
                method="POST"
                class="w-4/5">
            @csrf

            <div class="flex flex-col gap-3">

                <x-form.input field-name="firstName"
                              input-type="text"
                              value="Max"
                              label="First Name"
                              :full-col="true"
                              :required="true"
                />

                <x-form.input field-name="lastName"
                              input-type="text"
                              value="Musterman"
                              label="Last Name"
                              :full-col="true"
                              :required="true"
                />
            </div>

        </form>
    </div>
@endsection
