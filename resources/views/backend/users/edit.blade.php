@use(App\Enums\Role)
@use(App\Models\Role as ModelRole)
@use(App\Enums\ApplicationStatus)
@use(Carbon\Carbon)

@extends('layouts.backend')

@section('content')
    <div class="mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
                dark:text-white dark:border-white"
    >
        Edit user
    </div>

    <div class="flex justify-center content-center  py-2 px-2">
        <form action="{{ route('users.update',$user) }}"
              method="POST"
              class="w-4/5">
            @csrf
            @method('PATCH')

            <div class="flex flex-col gap-6">

                <x-form.input field-name="username"
                              input-type="text"
                              :value="$user->username"
                              label="Username"
                              :full-col="true"
                              :read-only="true"
                />

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

                <x-form.select2-multiple field-name="roles"
                                         :model="$user"
                                         label="Roles"
                                         :items="ModelRole::all()"
                                         select-class="select2-tides" />

                <div class="col-span-7 mt-10 w-4/5 space-x-4">
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

        <div class="space-y-5 w-1/5 h-full pr-4">
            @if($user->hasRole(Role::MODERATOR) && $user->channels->count() == 0)
                <div
                    class="mx-4 h-full w-full rounded-md border bg-white px-4 py-4 font-normal dark:bg-gray-800 dark:border-blue-800">
                    <h2 class="mb-3 -ml-5 border-l-4 border-blue-600 dark:border-blue-800 py-4 pl-4 text-xl
    dark:text-white"
                    >
                        User Channel
                    </h2>
                    <form action="{{route('channels.activate')}}"
                          method="POST">
                        @csrf
                        <input type="text"
                               name="username"
                               value="{{ $user->username}}"
                               hidden
                        />
                        <x-button type="submit"
                                  class="bg-fuchsia-600 hover:bg-fuchsia:700 w-full text-center content-center">
                            Enable user channel
                        </x-button>
                    </form>
                </div>
        </div>

        @elseif($user->channels()->count() > 0)
            <div class="m-2 rounded-lg border-2 border-solid border-green-500 dark:border-green-950 p-2">
                <div class="flex place-content-around justify-between">
                    <div>
                        <h3 class="pb-6 font-semibold dark:text-white">
                            {{ $user->channels()->first()->name }} Channel
                        </h3>
                    </div>
                    <div>
                        <x-heroicon-o-check-circle class="h-6 w-6 rounded text-green-600" />
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <x-heroicon-o-user class="h-6 w-5 dark:text-white" />
                    <span>{{ $user->channels()->first()->owner->getFullNameAttribute() }}</span>
                </div>
                <div class="pt-5">
                    <a href="{{ route('channels.edit', $user->channels()->first()) }}" class="flex flex-row">
                        <x-button type="button"
                                  class="flex w-full content-center justify-between bg-blue-600 hover:bg-blue-700">
                            <div>
                                Go to channel edit page
                            </div>
                            <div>
                                <x-heroicon-o-arrow-circle-right class="w-6" />
                            </div>
                        </x-button>
                    </a>
                </div>
            </div>
        @endif
    </div>



    @if(isset($user->settings->data['admin_portal_application_status']))
        @if($user->settings->data['admin_portal_application_status'] === ApplicationStatus::IN_PROGRESS())
            <div class="pt-10 mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
                dark:text-white dark:border-white"
            >
                Applications
            </div>
            <div class="flex flex-row items-center pt-5">
                <div class="pr-10 text-lg font-normal dark:text-white">
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
            <div class="flex flex-row items-center pt-5 dark:text-white">
                <div class="pr-10 text-lg">
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
