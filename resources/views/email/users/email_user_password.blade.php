@component('mail::message')
# Tides password

Hi ,

This is your password {{ $password }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
