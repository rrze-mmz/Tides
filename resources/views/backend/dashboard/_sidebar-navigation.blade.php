<a
    href="{{route('dashboard')}}"
    class="flex items-left text-white {{ setActiveLink(route('dashboard')) }}   hover:opacity-100 hover:mx-2
    hover:rounded py-4 pl-6 nav-item "
>
    {{ __('common.menu.dashboard') }}
</a>
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
    href="{{route('channels.index')}}"
    class="flex items-left text-white hover:opacity-100 hover:mx-2 hover:rounded py-4
        pl-6 nav-item {{ setActiveLink(route('channels.index')) }}"
>
    {{ trans_choice('common.menu.channel', 2) }}
</a>
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
@endcan

@can('administrate-admin-portal-pages')
    <a
        href="{{ route('devices.index') }}"
        class="flex items-left text-white hover:opacity-100 hover:mx-2 hover:rounded py-4
        pl-6 nav-item  {{ setActiveLink(route('devices.index')) }}"
    >
        {{ trans_choice('common.menu.device', 2) }}
    </a>

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
@can('administrate-superadmin-portal-pages')
    <div class="pt-5">
        <div class="flex items-left text-black font-light py-4 px-2
          placeholder-black">
            <form action="{{route('goto.series')}}" method="POST">
                @csrf
                <input class="mt-2 w-full rounded" type="text" name="seriesID" placeholder="Series ID" />
            </form>
        </div>

        <div class="flex items-left text-black  font-light py-4 px-2
          placeholder-black">
            <form action="{{route('goto.clip')}}" method="POST">
                @csrf
                <input class="mt-2 w-full rounded" type="text" name="clipID" placeholder="Clip ID" />
            </form>
        </div>
    </div>

@endcan
