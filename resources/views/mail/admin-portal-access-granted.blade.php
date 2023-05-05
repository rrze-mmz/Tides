<x-mail::message>
    Guten Tag {{ $user->getFullNameAttribute() }}

    # Introduction
    Your application for admin portal has be proccessed

    You can now access Admin port


    <x-mail::button :url="route('dashboard')">
        Go to admin portal Dashboard
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
