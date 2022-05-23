<aside class="mx-6 my-4 text-center text-white">
    <ul>
        <li class="w-full">
            <a
                href="{{route('dashboard')}}"
                class="block  py-2 mb-4 text-lg font-bold {{ setActiveLink(route('dashboard')) }}
                    border-white hover:text-gray-200"
            >
                {{ __('common.menu.dashboard') }}
            </a>
        </li>
        <li>
            <a
                href="{{route('series.index')}}"
                class="block mb-4 text-lg font-bold  {{ setActiveLink(route('series.index')) }} hover:text-gray-200"
            >
                {{ __('common.menu.series') }}
            </a>
        </li>
        <li>
            <a
                href="{{route('clips.index')}}"
                class="block mb-4 text-lg {{ setActiveLink(route('clips.index')) }}  font-bold hover:text-gray-200"
            >
                {{ trans_choice('common.menu.clip', 2) }}
            </a>
        </li>
        @can('view-assistant-menu-items')
            <li>
                <a
                    href="{{ route('presenters.index') }}"
                    class="block mb-4 text-lg  {{ setActiveLink(route('presenters.index')) }} font-bold
                    hover:text-gray-200"
                >
                    {{ trans_choice('common.menu.presenter', 2) }}
                </a>
            </li>
            <li>
                <a
                    href="{{ route('activities.index') }}"
                    class="block mb-4 text-lg  {{ setActiveLink(route('activities.index')) }} font-bold
                    hover:text-gray-200"
                >
                    {{ trans_choice('menu.backend.activity', 2)  }}
                </a>
            </li>
        @endcan

        @can('view-admin-menu-items')
            <li>
                <a
                    href="{{ route('devices.index') }}"
                    class="block mb-4 text-lg  {{ setActiveLink(route('devices.index')) }} font-bold
                    hover:text-gray-200"
                >
                    {{ trans_choice('common.menu.device', 2) }}
                </a>
            </li>

            <li>
                <a
                    href="{{ route('collections.index') }}"
                    class="block mb-4 text-lg  {{ setActiveLink(route('collections.index')) }} font-bold
                    hover:text-gray-200"
                >
                    {{ trans_choice('common.menu.collection', 2) }}
                </a>
            </li>

            <li>
                <a
                    href="{{ route('users.index') }}"
                    class="block mb-4 text-lg  {{ setActiveLink(route('users.index')) }} font-bold hover:text-gray-200"
                >
                    {{ trans_choice('common.menu.user', 2) }}
                </a>
            </li>
            <li>
                <a
                    href="{{route('opencast.status')}}"
                    class="block mb-4 text-lg {{ setActiveLink(route('opencast.status')) }} font-bold
                    hover:text-gray-200"
                >
                    {{ __('common.menu.opencast') }}
                </a>
            </li>
        @endcan
        @can('view-superadmin-menu-items')
            <li>
                <a
                    href="/"
                    class="block mb-4 text-lg font-bold hover:text-gray-200"
                >Server</a>
            </li>
        @endcan
    </ul>
</aside>
