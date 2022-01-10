@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Edit Presenter
    </div>

    <div class="flex py-2 px-2">
        <form action="{{ route('presenters.update',$presenter) }}"
              method="POST"
              class="w-4/5">
            @csrf
            @method('PATCH')

            <div class="flex flex-col gap-6">

                <x-form.input field-name="degree_title"
                              input-type="text"
                              :value="$presenter->degree_title"
                              label="Degree Title"
                              :full-col="false"
                              :required="false"
                />

                <x-form.input field-name="first_name"
                              input-type="text"
                              :value="$presenter->first_name"
                              label="First Name"
                              :full-col="true"
                              :required="true"
                />

                <x-form.input field-name="last_name"
                              input-type="text"
                              :value="$presenter->last_name"
                              label="Last Name"
                              :full-col="true"
                              :required="true"
                />

                <x-form.input field-name="username"
                              input-type="username"
                              :value="$presenter->username"
                              label="Username"
                              :full-col="true"
                              :required="true"
                />

                <x-form.input field-name="email"
                              input-type="email"
                              :value="$presenter->email"
                              label="Email"
                              :full-col="true"
                              :required="true"
                />

                <div class="col-span-7 w-4/5">
                    <x-form.button :link="$link=false" type="submit" text="Edit presenter"/>
                    <x-form.button :link="route('presenters.index')" type="back" text="Back to presenters index"/>
                </div>
            </div>
        </form>
    </div>

    <div class="flex pt-6 pb-2 font-semibold border-b border-black font-2xl">
        {{ $presenter->getFullNameAttribute() }} appears on these series
    </div>
    <div class="grid grid-cols-3 gap-4 pt-8 h48">
        @forelse($presenter->series as $series)
            @include('backend.series._card',['series'=> $series])
        @empty
            No series found
        @endforelse
    </div>

    <div class="flex pt-6 pb-2 font-semibold border-b border-black font-2xl">
        {{ $presenter->getFullNameAttribute() }} appears on these clips
    </div>
    <div class="grid grid-cols-3 gap-4 pt-8 h48">
        @forelse($presenter->clips as $clip)
            @include('backend.clips._card',['clip'=> $clip])
        @empty
            No clips found
        @endforelse
    </div>

@endsection
