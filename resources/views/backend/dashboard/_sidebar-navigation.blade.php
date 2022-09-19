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
@can('view-assistant-menu-items')
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

@can('view-admin-menu-items')
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
@can('view-superadmin-menu-items')
    <a
        href="{{route('systems.status')}}"
        class="flex items-center text-white opacity-75 hover:opacity-100 py-4
        pl-6 nav-item  {{ setActiveLink(route('systems.status')) }}"
    >
        {{ trans_choice('common.menu.system', 2) }}
    </a>

    <a
        href="{{route('portal.settings')}}"
        class="flex items-center text-white opacity-75 hover:opacity-100 py-4
        pl-6 nav-item  {{ setActiveLink(route('portal.settings')) }}"
    >
        {{ __('common.menu.portal settings') }}
    </a>
@endcan
@can('view-superadmin-menu-items')
    <div class="pt-10git ">
        <div class="flex items-center text-black py-4
        pl-6 nav-item placeholder-black">
            <form action="{{route('goto.series')}}" method="POST">
                @csrf
                <input class="rounded w-1/2 mt-2" type="text" name="seriesID" placeholder="Series ID"/>
            </form>
        </div>

        <div class="flex items-center text-black py-4
        pl-6 nav-item ">
            <form action="{{route('goto.clip')}}" method="POST">
                @csrf
                <input class="rounded w-1/2 mt-2" type="text" name="clipID" placeholder="Clip ID"/>
            </form>
        </div>
    </div>

@endcan
