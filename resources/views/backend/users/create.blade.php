@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 font-semibold font-2xl dark:text-white dark:border-white">
        Create new user
    </div>

    <div class="flex px-2 py-2">
        <form action="{{ route('users.store') }}"
              method="POST"
              class="w-4/5">
            @csrf

            <div class="flex flex-col gap-6 space-y-4 pt-10">

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
                    <x-form.button :link="$link=false" type="submit" text="Create user" />
                </div>
            </div>

        </form>
    </div>
@endsection
