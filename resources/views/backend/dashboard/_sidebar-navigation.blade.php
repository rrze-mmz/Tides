<a
    href="{{route('dashboard')}}"
    class="flex items-left text-white {{ setActiveLink(route('dashboard')) }}   hover:opacity-100 hover:mx-2
    hover:rounded py-4 pl-6 my-4 nav-item "
>
    {{ __('common.menu.dashboard') }}
</a>
<div class="flex items-left py-4 pl-4 italic font-extrabold text-lg">
    Main Objects
</div>
<a
    href="{{route('series.index')}}"
    class="flex items-left text-white hover:opacity-100 hover:mx-2 hover:rounded py-4
        pl-6 nav-item {{ setActiveLink(route('series.index')) }}"
>
    {{ __('common.menu.series') }}
</a>
<a
    href="{{route('clips.index')}}"
    class="flex items-left text-white hover:opacity-100 hover:mx-2 hover:rounded py-4
        pl-6 nav-item {{ setActiveLink(route('clips.index')) }}"
>
    {{ trans_choice('common.menu.clip', 2)}}
</a>
<a
    href="{{route('podcasts.index')}}"
    class="flex items-left text-white hover:opacity-100 hover:mx-2 hover:rounded py-4
        pl-6 nav-item {{ setActiveLink(route('podcasts.index')) }}"
>
    {{ trans_choice('common.menu.podcast', 2)}}
</a>
<a
    href="{{route('channels.index')}}"
    class="flex items-left text-white hover:opacity-100 hover:mx-2 hover:rounded py-4
        pl-6 nav-item {{ setActiveLink(route('channels.index')) }}"
>
    {{ trans_choice('common.menu.channel', 2) }}
</a>

<div class="flex items-left py-4 pl-4 italic font-extrabold text-lg">
    Portal resources
</div>
<a
    href="{{route('images.index')}}"
    class="flex items-left text-white hover:opacity-100 hover:mx-2 hover:rounded py-4
        pl-6 nav-item {{ setActiveLink(route('images.index')) }}"
>
    {{ trans_choice('common.menu.image', 2) }}
</a>

@can('administrate-portal-pages')
    <a
        href="{{ route('presenters.index') }}"
        class="flex items-left text-white hover:opacity-100 hover:mx-2 hover:rounded py-4
        pl-6 nav-item  {{ setActiveLink(route('presenters.index')) }}"
    >
        {{ trans_choice('common.menu.presenter', 2) }}
    </a>
    <a
        href="{{ route('activities.index') }}"
        class="flex items-left text-white hover:opacity-100 hover:mx-2 hover:rounded py-4
        pl-6 nav-item {{ setActiveLink(route('activities.index')) }}"
    >
        {{ trans_choice('common.menu.activity', 2)  }}
    </a>
    <a
        href="{{ route('livestreams.index') }}"
        class="flex items-left text-white hover:opacity-100 hover:mx-2 hover:rounded py-4
        pl-6 nav-item {{ setActiveLink(route('livestreams.index')) }}"
    >
        Livestreams
    </a>
    <a
        href="{{ route('devices.index') }}"
        class="flex items-left text-white hover:opacity-100 hover:mx-2 hover:rounded py-4
        pl-6 nav-item  {{ setActiveLink(route('devices.index')) }}"
    >
        {{ trans_choice('common.menu.device', 2) }}
    </a>
@endcan

@can('administrate-admin-portal-pages')
    <a
        href="{{ route('articles.index') }}"
        class="flex items-left text-white hover:opacity-100 hover:mx-2 hover:rounded py-4
        pl-6 nav-item  {{ setActiveLink(route('articles.index')) }}"
    >
        {{ trans_choice('common.menu.article', 2) }}
    </a>

    <a
        href="{{ route('collections.index') }}"
        class="flex items-left text-white hover:opacity-100 hover:mx-2 hover:rounded py-4
        pl-6 nav-item {{ setActiveLink(route('collections.index')) }}"
    >
        {{ trans_choice('common.menu.collection', 2) }}
    </a>
    <div class="flex items-left py-4 pl-4 italic font-extrabold text-lg w-full pr-2">
        Portal Administration
    </div>
    <a
        href="{{ route('users.index') }}"
        class="flex items-left text-white hover:opacity-100 hover:mx-2 hover:rounded py-4
        pl-6 nav-item {{ setActiveLink(route('users.index')) }}"
    >
        {{ trans_choice('common.menu.user', 2) }}
    </a>
@endcan
@can('administrate-superadmin-portal-pages')

    <a
        href="{{route('systems.status')}}"
        class="flex items-left text-white hover:opacity-100 hover:mx-2 hover:rounded py-4
        pl-6 nav-item  {{ setActiveLink(route('systems.status')) }}"
    >
        {{ __('common.menu.portal settings') }}
    </a>
@endcan
