<a
    href="{{route('dashboard')}}"
    class="flex items-center text-white {{ setActiveLink(route('dashboard')) }} py-4 pl-6 nav-item "
>
    {{ __('common.menu.dashboard') }}
</a>
<a
    href="{{route('series.index')}}"
    class="flex items-center text-white opacity-75 hover:opacity-100 py-4
        pl-6 nav-item {{ setActiveLink(route('series.index')) }}"
>
    {{ __('common.menu.series') }}
</a>
<a
    href="{{route('clips.index')}}"
    class="flex items-center text-white opacity-75 hover:opacity-100 py-4
        pl-6 nav-item {{ setActiveLink(route('clips.index')) }}"
>
    {{ trans_choice('common.menu.clip', 2) }}
</a>

<a
    href="{{route('images.index')}}"
    class="flex items-center text-white opacity-75 hover:opacity-100 py-4
        pl-6 nav-item {{ setActiveLink(route('images.index')) }}"
>
    {{ trans_choice('common.menu.image', 2) }}
</a>

@can('administrate-portal-pages')
    <a
        href="{{ route('presenters.index') }}"
        class="flex items-center text-white opacity-75 hover:opacity-100 py-4
        pl-6 nav-item  {{ setActiveLink(route('presenters.index')) }}"
    >
        {{ trans_choice('common.menu.presenter', 2) }}
    </a>
    <a
        href="{{ route('activities.index') }}"
        class="flex items-center text-white opacity-75 hover:opacity-100 py-4
        pl-6 nav-item {{ setActiveLink(route('activities.index')) }}"
    >
        {{ trans_choice('common.menu.activity', 2)  }}
    </a>
@endcan

@can('administrate-admin-portal-pages')
    <a
        href="{{ route('devices.index') }}"
        class="flex items-center text-white opacity-75 hover:opacity-100 py-4
        pl-6 nav-item  {{ setActiveLink(route('devices.index')) }} font-bold"
    >
        {{ trans_choice('common.menu.device', 2) }}
    </a>

    <a
        href="{{ route('collections.index') }}"
        class="flex items-center text-white opacity-75 hover:opacity-100 py-4
        pl-6 nav-item {{ setActiveLink(route('collections.index')) }}"
    >
        {{ trans_choice('common.menu.collection', 2) }}
    </a>
    <a
        href="{{ route('users.index') }}"
        class="flex items-center text-white opacity-75 hover:opacity-100 py-4
        pl-6 nav-item {{ setActiveLink(route('users.index')) }}"
    >
        {{ trans_choice('common.menu.user', 2) }}
    </a>
@endcan
@can('administrate-superadmin-portal-pages')
    <a
        href="{{route('settings.portal.index')}}"
        class="flex items-center text-white opacity-75 hover:opacity-100 py-4
        pl-6 nav-item  {{ setActiveLink(route('settings.portal.index')) }}"
    >
        {{ __('common.menu.portal settings') }}
    </a>
    <a
        href="{{route('systems.status')}}"
        class="flex items-center text-white opacity-75 hover:opacity-100 py-4
        pl-6 nav-item  {{ setActiveLink(route('systems.status')) }}"
    >
        {{ trans_choice('common.menu.system', 2) }}
    </a>
    <a
        href="{{route('user.notifications')}}"
        class="flex items-center text-white opacity-75 hover:opacity-100 py-4
        pl-6 nav-item  {{ setActiveLink(route('settings.portal.index')) }}"
    >
        <div class="flex items-center">
            Notifications
            @if ($counter = auth()->user()->unreadNotifications->count() > 0)
                <span class="rounded-full  p-1.5 ml-1 bg-white text-green-700 text-sm"> {{ $counter }}</span>
            @endif
        </div>

    </a>
@endcan
@can('administrate-superadmin-portal-pages')
    <div class="pt-5 ">
        <div class="flex items-center text-black font-light py-4 px-2
          placeholder-black">
            <form action="{{route('goto.series')}}" method="POST">
                @csrf
                <input class="rounded w-full mt-2" type="text" name="seriesID" placeholder="Series ID"/>
            </form>
        </div>

        <div class="flex items-center text-black  font-light py-4 px-2
          placeholder-black">
            <form action="{{route('goto.clip')}}" method="POST">
                @csrf
                <input class="rounded w-full mt-2" type="text" name="clipID" placeholder="Clip ID"/>
            </form>
        </div>
    </div>

@endcan
