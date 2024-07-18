@component('mail::message')
    # Video upload completed

    Hi {{ $clip->owner->name }},

    Your Video "{{ $clip->title }}" is online

    @component('mail::button', ['url' => route('frontend.clips.show', $clip)])
        Watch the video
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
