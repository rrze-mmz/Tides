<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @livewireStyles
</head>
<body class="h-screen font-sans antialiased leading-none bg-gray-100">
<div id="app">
    <header class="fixed top-0 z-10 p-2 py-4 mt-0 w-full bg-gray-800">
        <nav class="flex justify-between items-center">
            <div class="px-6 ">
                <a href="{{ url('/') }}"
                   class="text-lg font-semibold text-gray-100 no-underline"
                >
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>
            <nav class="space-x-4 text-sm text-gray-300 sm:text-base px-8">

                @guest
                    <a class="no-underline hover:underline"
                       href="{{ route('login') }}"
                    >{{ __('Login') }}</a>
                    @if (Route::has('register'))
                        <a class="no-underline hover:underline"
                           href="{{ route('register') }}"
                        >{{ __('Register') }}</a>
                    @endif
                @else

                    @if(!str_contains(url()->current(), 'admin'))
                        <a href="/admin/dashboard"
                           class="no-underline hover:underline"
                        > Dashboard </a>
                    @endif

                    <span>Hi, {{ Auth::user()->getFullNameAttribute() }}</span>

                    <a href="{{ route('logout') }}"
                       class="no-underline hover:underline"
                       onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                    <form id="logout-form"
                          action="{{ route('logout') }}"
                          method="POST"
                          class="hidden">
                        {{ csrf_field() }}
                    </form>
                @endguest
            </nav>
        </nav>
    </header>
    <main class="pt-12 mx-auto lg:flex">
        <div class="min-h-screen max-h-full  bg-gray-800 w-1/7">
            @include('backend.dashboard._sidebar-navigation')
        </div>
        <div class="pt-8 w-full lg:flex-grow lg:mx-10 pb-10">
            @if(Session::has('flashMessage'))
                <x-alerts.flash-alert :message="Session::get('flashMessage', 'default')"/>
            @endif
            @yield('content')
        </div>
    </main>
    <footer class=" flex bg-gray-800 h-10 justify-center items-center">
        <div class="space-x-4 text-sm text-gray-300 sm:text-base">
            Copyright @ {{ Illuminate\Support\Carbon::now()->year }} MIT Licence
        </div>
    </footer>
</div>
</body>
</html>
