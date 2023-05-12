@php
    use App\Enums\ApplicationStatus;
    use Carbon\Carbon;
@endphp

@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Edit user
    </div>

    <div class="flex py-2 px-2">
        <form action="{{ route('users.update',$user) }}"
              method="POST"
              class="w-4/5">
            @csrf
            @method('PATCH')

            <div class="flex flex-col gap-6">

                <x-form.input field-name="first_name"
                              input-type="text"
                              :value="$user->first_name"
                              label="First Name"
                              :full-col="true"
                              :required="true"
                />

                <x-form.input field-name="last_name"
                              input-type="text"
                              :value="$user->last_name"
                              label="Last Name"
                              :full-col="true"
                              :required="true"
                />

                <x-form.input field-name="email"
                              input-type="email"
                              :value="$user->email"
                              label="Email"
                              :full-col="true"
                              :required="true"
                />

                <x-form.select2-single field-name="role_id"
                                       label="Role"
                                       select-class="select2-tides"
                                       model="role"
                                       :selectedItem="$user->roles->first()?->id"
                />

                <div class="col-span-7 w-4/5 mt-10">
                    <x-button class="bg-blue-600 hover:bg-blue700">
                        Update user
                    </x-button>
                    <a href="{{route('users.index')}}">
                        <x-button type="button" class="bg-gray-600 hover:bg-gray:700">
                            Back to users list
                        </x-button>
                    </a>
                </div>
            </div>

        </form>
    </div>

    <div class="flex pt-10 py-2 font-semibold border-b border-black font-2xl">
        Applications
    </div>

    @if(isset($user->settings->data['admin_portal_application_status']))
        @if($user->settings->data['admin_portal_application_status'] === ApplicationStatus::IN_PROGRESS())
            <div class="flex flex-row pt-5 items-center">
                <div class="pr-10 text-lg font-bold">
                    User requested access to admin portal
                </div>
                <div>
                    <form action="{{route('admin.portal.application.grant')}}"
                          method="POST">
                        @csrf
                        <input type="text"
                               name="username"
                               value="{{ $user->username}}"
                               hidden
                        />

                        <x-button class="bg-green-700 hover:bg-green-700">
                            Grant user access to admin portal
                        </x-button>
                    </form>
                </div>
            </div>
        @else
            <div class="flex flex-row pt-5 items-center">
                <div class="pr-10 text-lg ">
                    User admin portal application processed by
                    <span class="italic"> {{ $user->settings->data['admin_portal_application_processed_by'] }} </span>
                    <span class="text-sm">
                    {{ Carbon::createFromFormat(
                    'Y-m-d H-i-s',$user->settings->data['admin_portal_application_processed_at']
                    )->diffForHumans()  }}
                </span>
                </div>
            </div>
        @endif
    @endif
@endsection
