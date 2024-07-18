@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black text-2xl flex-col dark:text-white dark:border-white font-normal pb-2">
        <div class="flex w-full items-center">
            Create new presenter
        </div>
    </div>

    <div class="flex px-2 py-2">
        <form action="{{ route('presenters.store') }}"
              method="POST"
              class="w-4/5">
            @csrf

            <div class="flex flex-col gap-6">

                <x-form.select2-single field-name="academic_degree_id"
                                       label="Degree title"
                                       select-class="select2-tides"
                                       model="academicDegree"
                                       :full-col="false"
                                       :selectedItem="(old('academic_degree_id'))?? 0 "
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
                    <x-form.button :link="$link=false" type="submit" text="Create user" />
                </div>
            </div>

        </form>
    </div>
@endsection
