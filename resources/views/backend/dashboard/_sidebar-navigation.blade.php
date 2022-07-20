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
                    {{ trans_choice('common.menu.activity', 2)  }}
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
        @endcan
        @can('view-superadmin-menu-items')
            <li>
                <a
                    href="{{route('systems.status')}}"
                    class="block mb-4 text-lg {{ setActiveLink(route('systems.status')) }} font-bold
                    hover:text-gray-200"
                >
                    {{ trans_choice('common.menu.system', 2) }}
                </a>
            </li>
        @endcan
    </ul>
    @can('view-superadmin-menu-items')
        <div class="mt-8 pt-2 block w-full items-center">
            <h4 class="font-bold">
                Go to
            </h4>
        </div>

        <div class="flex justify-center text-black">
            <form action="{{route('goto.series')}}" method="POST">
                @csrf
                <input class="rounded w-1/2 mt-2" type="text" name="seriesID" placeholder="Series ID"/>
            </form>
        </div>

        <div class="flex justify-center text-black">
            <form action="{{route('goto.clip')}}" method="POST">
                @csrf
                <input class="rounded w-1/2 mt-2" type="text" name="clipID" placeholder="Clip ID"/>
            </form>
        </div>

    @endcan
</aside>
