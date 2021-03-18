<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" ></script>
    <script src="{{ asset('js/plyr.js') }}" ></script>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plyr.css') }}" rel="stylesheet">
</head>
<body class=" font-sans antialiased leading-none bg-gray-100">
    <div id="app">
        <header class="fixed top-0 z-10 p-2 py-4 mt-0 w-full bg-gray-800 ">
            <nav class="container flex justify-between items-center px-6 mx-auto">
                <div>
                    <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100 no-underline">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>
                <nav class="space-x-4 text-sm text-gray-300 sm:text-base">

                    @guest
                        <a class="no-underline hover:underline" href="{{ route('login') }}">{{ __('Login') }}</a>
                        @if (Route::has('register'))
                            <a class="no-underline hover:underline" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    @else

                        @if(!str_contains(url()->current(), 'admin'))
                           <a href="/admin/dashboard" class="no-underline hover:underline"> Dashboard </a>
                        @endif

                        <span>{{ Auth::user()->name }}</span>

                        <a href="{{ route('logout') }}"
                           class="no-underline hover:underline"
                           onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            {{ csrf_field() }}
                        </form>
                    @endguest
                </nav>
            </nav>
        </header>
        <div class="h-full min-h-screen">
            @yield('content')
        </div>
    </div>

    <footer class="flex bg-gray-800 h-10 mt-6 justify-center items-center">
        <div class="space-x-4 text-sm text-gray-300 sm:text-base">
            Copyright @ {{ Illuminate\Support\Carbon::now()->year }} MIT Licence
        </div>
    </footer>
    <script type="text/javascript">
            const player = new Plyr('#player',{
                iconUrl: '/css/plyr.svg',
                loadSprite: false,
            });
    </script>
</body>
</html>
