<nav class="container mx-auto flex items-center justify-between px-6">
    <div>
        <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100 no-underline">
            {{ config('app.name', 'Laravel') }}
        </a>
    </div>
    <nav class="font-semibold text-gray-300 space-x-4 sm:text-base">
        <span class="no-underline hover:underline">
            <a href="{{ route('frontend.series.index') }}" class="text-lg text-white">
                Series
            </a>
        </span>
        <span class="no-underline hover:underline">
            <a href="{{ route('frontend.clips.index') }}" class="text-lg text-white">
                Clips
            </a>
        </span>
        <span class="no-underline hover:underline">
            <a href="{{ route('frontend.organizations.index') }}" class="text-lg text-white">
                Organizations
            </a>
        </span>
        <span class="pr-10 no-underline hover:underline">
            <a href="{{ route('frontend.series.index') }}" class="text-lg text-white">
                Live now!
            </a>
        </span>
    </nav>
    <nav class="text-sm font-semibold text-gray-300 space-x-4 sm:text-base">

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
        <span class="mr-10 no-underline">
            <a href="/set_lang/en" class="{{ (session('locale') === 'en')?'underline':'' }}">EN</a> |
            <a href="/set_lang/de" class="{{ (session('locale') === 'de')?'underline':'' }}">DE</a>
        </span>
    </nav>
</nav>
