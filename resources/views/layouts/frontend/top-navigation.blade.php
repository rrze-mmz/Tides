<nav class="container mx-auto flex items-center justify-between px-2 w-full">
    <div class="flex">
        <a href="{{ url('/') }}"
           class="font-bold  no-underline text-ur "
        >
            <div class="flex flex-col">
                <div>
                    <span class="text-2xl">
                            {{ config('app.name', 'Laravel') }}
                    </span>
                </div>
                <div>
            <span class="text-sm">
                [develop/unstable]
            </span>
                </div>
            </div>
        </a>
    </div>
    <nav class="font-semibold space-x-4  text-lg">
        <span class="pl-10 no-underline ">
            <a href="{{ route('frontend.channels.index') }}">
                Channels
            </a>
        </span>
        <span class="no-underline ">
            <a href="{{ route('frontend.series.index') }}">
                Series
            </a>
        </span>
        <span class="no-underline ">
            <a href="{{ route('frontend.clips.index') }}">
                Clips
            </a>
        </span>
        <span class="no-underline ">
            <a href="{{ route('frontend.podcasts.index') }}">
                Podcasts
            </a>
        </span>
        <span class="no-underline ">
            <a href="{{ route('frontend.organizations.index') }}">
                Organizations
            </a>
        </span>
        <span class="no-underline ">
            <a href="{{ route('frontend.livestreams.index') }}">
                Live now!
            </a>
        </span>
        <span class="pr-10 no-underline ">
            <a href="{{ route('frontend.faq') }}">
                FAQs
            </a>
        </span>
    </nav>
    <nav class=" flex font-semibold text-md space-x-4 ">
        @guest
            <a href="{{ route('login') }}"
            >
                {{ __('auth.Login') }}
            </a>
        @else
            <span>Hi, {{ Auth::user()->getFullNameAttribute() }}</span>
            <a href="{{route('frontend.userSettings.edit')}}">
                my{{ str(config('app.name'))->ucfirst() }}
            </a>
            @if(!str_contains(url()->current(), 'admin') && auth()->user()->can('access-dashboard'))
                <a href="/admin/dashboard"
                >
                    Dashboard
                </a>
            @endif

            <a href="{{ route('logout') }}"
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
        <div class="flex px-2">
            <x-theme-toogle />
        </div>
        <!-- Dark Mode Toggle Component -->
        <span class="mr-10 no-underline">
            <a href="/set_lang/en" class="{{ (session('locale') === 'en')?'underline':'' }}">EN</a> |
            <a href="/set_lang/de" class="{{ (session('locale') === 'de')?'underline':'' }}">DE</a>
        </span>

    </nav>
</nav>
