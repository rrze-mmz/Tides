<aside class="my-4 mx-6 text-white text-center">
    <ul>
        <li class="w-full">
            <a
                href="{{route('dashboard')}}"
                class="block  py-2 mb-4 text-lg font-bold {{ setActiveLink(route('dashboard')) }}
                    border-white hover:text-gray-200"
            >Dashboard</a>
        </li>
        <li>
            <a
                href="{{route('series.index')}}"
                class="block mb-4 text-lg font-bold  {{ setActiveLink(route('series.index')) }} hover:text-gray-200"
            >Series</a>
        </li>
        <li>
            <a
                href="{{route('clips.index')}}"
                class="block mb-4 text-lg {{ setActiveLink(route('clips.index')) }}  font-bold hover:text-gray-200"
            >Clips</a>
        </li>
        @if(auth()->user()->isAdmin())
            <li>
                <a
                    href="{{ route('presenters.index') }}"
                    class="block mb-4 text-lg  {{ setActiveLink(route('presenters.index')) }} font-bold hover:text-gray-200"
                >Presenter</a>
            </li>
            <li>
                <a
                    href="{{ route('users.index') }}"
                    class="block mb-4 text-lg  {{ setActiveLink(route('users.index')) }} font-bold hover:text-gray-200"
                >Users</a>
            </li>
            <li>
                <a
                    href="{{route('opencast.status')}}"
                    class="block mb-4 text-lg {{ setActiveLink(route('opencast.status')) }} font-bold hover:text-gray-200"
                >Opencast</a>
            </li>
            <li>
                <a
                    href="/"
                    class="block mb-4 text-lg font-bold hover:text-gray-200"
                >Server</a>
            </li>
        @endif
    </ul>
</aside>
