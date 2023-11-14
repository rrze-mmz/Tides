@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 font-semibold font-2xl">
        Edit Presenter
    </div>

    <div class="flex w-full gap-4">
        <div class="grow content-center content-between justify-center  px-2 py-2">
            <form action="{{ route('presenters.update',$presenter) }}"
                  method="POST"
                  class="w-4/5">
                @csrf
                @method('PATCH')
                <div class="flex flex-col gap-6">

                    <x-form.select2-single field-name="academic_degree_id"
                                           label="Degree title"
                                           select-class="select2-tides"
                                           model="academicDegree"
                                           :selectedItem="$presenter->academic_degree_id"
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
                        <x-form.button :link="$link=false"
                                       type="submit"
                                       text="Update presenter"
                        />

                        <x-form.button :link="route('presenters.index')"
                                       type="back"
                                       text="Back to presenters index"
                                       color="green"
                        />
                    </div>
                </div>
            </form>
        </div>
        <div class="mr-10 flex-none flex-col pt-10">
            <img class="h-28 w-auto object-contain rounded-[0.25rem] "
                 src="{{ $presenter->getImageUrl() }}"
                 alt="{{ $presenter->image?->description }}">
        </div>
    </div>
    <div>
        @include('backend.users.series._layout',[
                            'layoutHeader'=> $presenter->getFullNameAttribute() .' appears on these series',
                             'series' => $presenter->series()->withLastPublicClip()->orderByDesc('created_at')->paginate()
        ])

        @include('backend.users.clips._layout',[
                            'layoutHeader' => $presenter->getFullNameAttribute().' appears on these clips',
                            'clips' => $presenter->clipsWithoutSeries()->sortByDesc('created_at'),
        ])
    </div>

@endsection
