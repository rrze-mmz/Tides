<x-mail::message>

## New Application for Tides Admin Portal

Member name : {{ $user->getFullNameAttribute() }}

Member username : {{ $user->username }}

Email : {{ $user->email }}

## Accepted Use Terms ##

> {{ __('dashboard.user.admin portal use terms') }}

Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
