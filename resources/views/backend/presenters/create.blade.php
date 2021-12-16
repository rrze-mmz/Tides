@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Create new presenter
    </div>

    <div class="flex py-2 px-2">
        <form action="{{ route('presenters.create') }}"
              method="POST"
              class="w-4/5">
            @csrf

            <div class="flex flex-col gap-6">

                <x-form.input field-name="degree_title"
                              input-type="text"
                              :value="old('degree_title')"
                              label="Degree title"
                              :full-col="false"
                              :required="false"
                />

                <x-form.input field-name="first_name"
                              input-type="text"
                              :value="old('first_name')"
                              label="First Name"
                              :full-col="true"
                              :required="true"
                />

                <x-form.input field-name="last_name"
                              input-type="text"
                              :value="old('last_name')"
                              label="Last Name"
                              :full-col="true"
                              :required="true"
                />

                <x-form.input field-name="username"
                              input-type="username"
                              :value="old('username')"
                              label="Username"
                              :full-col="false"
                              :required="true"
                />

                <x-form.input field-name="email"
                              input-type="email"
                              :value="old('email')"
                              label="Email"
                              :full-col="true"
                              :required="true"
                />

                <div class="col-span-7 w-4/5">
                    <x-form.button :link="$link=false" type="submit" text="Create user"/>
                </div>
            </div>

        </form>
    </div>
@endsection
