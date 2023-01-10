<nav class="container flex justify-between items-center px-6 mx-auto">
    <div>
        <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100 no-underline">
            {{ config('app.name', 'Laravel') }}
        </a>
    </div>
    <nav class="space-x-4 font-semibold text-gray-300 sm:text-base">
        <span class="no-underline hover:underline">
            <a href="{{ route('frontend.series.index') }}" class="text-white text-lg">
                Series
            </a>
        </span>
        <span class="no-underline hover:underline">
            <a href="{{ route('frontend.clips.index') }}" class="text-white text-lg">
                Clips
            </a>
        </span>
        <span class="no-underline hover:underline">
            <a href="{{ route('frontend.organizations.index') }}" class="text-white text-lg">
                Organizations
            </a>
        </span>
        <span class="no-underline hover:underline pr-10">
            <a href="{{ route('frontend.series.index') }}" class="text-white text-lg">
                Live now!
            </a>
        </span>
    </nav>
    <nav class="space-x-4 text-sm font-semibold text-gray-300 sm:text-base">

        @guest
            <a class="no-underline hover:underline"
               href="{{ route('login') }}"
            >{{ __('auth.Login') }}</a>
        @else
            <span>Hi, {{ Auth::user()->getFullNameAttribute() }}</span>
            <a href="{{route('frontend.userSettings.edit')}}" class="no-underline hover:underline">
                my{{ str(config('app.name'))->ucfirst() }}
            </a>
            @if(!str_contains(url()->current(), 'admin') && auth()->user()->can('access-dashboard'))
                <a href="/admin/dashboard"
                   class="no-underline hover:underline"
                > Dashboard </a>
            @endif

            <a href="{{ route('logout') }}"
               class="no-underline hover:underline"
               onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"
            >{{ __('auth.Logout') }}</a>
            <form id="logout-form"
                  action="{{ route('logout') }}"
                  method="POST"
                  class="hidden">
                {{ csrf_field() }}
            </form>
        @endguest
        <span class="no-underline mr-10">
            <a href="/set_lang/en" class="{{ (session('locale') === 'en')?'underline':'' }}">EN</a> |
            <a href="/set_lang/de" class="{{ (session('locale') === 'de')?'underline':'' }}">DE</a>
        </span>
    </nav>
</nav>
