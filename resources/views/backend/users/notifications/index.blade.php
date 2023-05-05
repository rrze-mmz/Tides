@php use App\Enums\ApplicationStatus; @endphp
@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black text-2xl">
        Notifications
    </div>

    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg mt-4">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
            <tr>
                <th class="px-6 py-3 text-left ">
                    <div class="flex items-center">
                        Notification type
                    </div>
                </th>
                <th class="px-6 py-3 text-left ">
                    <div class="flex items-center">
                        Information
                    </div>
                </th>
                <th class="px-6 py-3 text-left ">
                    <div class="flex items-center">
                        Status
                    </div>
                </th>
                <th class="px-6 py-3 text-left ">
                    <div class="flex items-center">
                        Actions
                    </div>
                </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse(auth()->user()->notifications as $notification)
                <tr class="@if(is_null($notification->read_at)) font-bold @endif">
                    <td class="w-3/12 px-6 py-4 whitespace-no-wrap">
                        @if($notification->type === 'App\Notifications\NewAdminPortalNotification')
                            Application
                        @else
                            {{ $notification->type }}
                        @endif
                    </td>
                    <td class="w-3/12 px-6 py-4 whitespace-no-wrap">
                        Requested from <span
                            class="font-bold">{{ $notification->data['username_applied_for_admin_portal'] }}</span>
                    </td>
                    <td class="w-3/12 px-6 py-4 whitespace-no-wrap">
                        {{ $notification->data['application_status'] }}
                    </td>
                    <td class="w-3/12 px-6 py-4 whitespace-no-wrap">
                        @if ($notification->data['application_status'] === ApplicationStatus::IN_PROGRESS())
                            <form action="{{route('admin.portal.application.grant')}}"
                                  method="POST">
                                @csrf
                                <input type="text"
                                       name="username"
                                       value="{{ $notification->data['username_applied_for_admin_portal']}}"
                                       hidden
                                />

                                <x-button class="bg-green-700 hover:bg-green-700">
                                    Assign moderator role
                                </x-button>
                            </form>
                        @else
                            <x-button class="bg-blue-600 hover:bg-blue-700">
                                View Moderator
                            </x-button>
                        @endif

                    </td>
                </tr>
            @empty
                <tr>
                    <td class="w-full px-6 py-4 whitespace-no-wrap">
                        You have no notifications
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

@endsection
