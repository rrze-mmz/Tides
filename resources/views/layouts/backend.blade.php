<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    @vite(['resources/css/app.css','resources/js/app.js'])
    @livewireStyles
    @trixassets
</head>
<body id="app">
<div class="flex bg-gray-100">
    <aside class="relative hidden h-screen w-1/12 shadow-xl bg-sidebar sm:block">
        <div class="p-6 text-center align-center">
            <a href="{{route('home')}}"
               class="text-3xl font-semibold text-white hover:text-gray-300">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>
        <nav class="pt-3 text-base font-semibold text-white">
            @include('backend.dashboard._sidebar-navigation')
        </nav>
        <a href="#"
           class="absolute bottom-0 flex w-full items-center justify-center py-4 text-white upgrade-btn active-nav-link">
            <x-heroicon-o-chevron-double-left class="h-6 w-6" />
        </a>
    </aside>

    <div class="relative flex h-screen w-full flex-col overflow-y-hidden">
        <!-- Desktop Header -->
        <header class="flex hidden w-full items-center justify-end bg-white px-6 sm:flex">
            <div class="w-1/2">
                @include('backend.search._searchbar')
            </div>
            <div x-data="{ isOpen: false }" class="relative flex justify-end">
                <x-heroicon-o-user @click="isOpen = !isOpen"
                                   class="relative z-10 w-8 h-8 rounded-full overflow-hidden border-2 border-gray-400
                                            hover:border-gray-300 focus:border-gray-300 focus:outline-none" />
                </button>
                <button x-show="isOpen"
                        @click="isOpen = false"
                        class="fixed inset-0 h-full w-full cursor-default">
                </button>
                <div x-show="isOpen"
                     class="absolute mt-16 w-48 items-center rounded-lg bg-white py-2 align-middle shadow-lg">
                    <a href="#" class="block px-4 py-2 hover:text-gray-400">Settings</a>
                    <a href="{{ route('user.notifications') }}" class="block px-4 py-2 hover:text-gray-400">
                        Notifications
                        @if (($counter = auth()->user()->unreadNotifications->count()) > 0)
                            <span
                                class="ml-1 rounded-full bg-white text-sm text-green-700 p-1.5"> {{ $counter }}</span>
                        @endif
                    </a>
                    <a href="{{ route('logout') }}"
                       class="block px-4 py-2 hover:text-gray-400"
                       onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">{{ __('auth.Logout') }}</a>
                    <form id="logout-form"
                          action="{{ route('logout') }}"
                          method="POST"
                          class="hidden">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </header>

        <!-- Mobile Header & Nav -->
        <header x-data="{ isOpen: false }" class="w-full px-6 py-5 bg-sidebar sm:hidden">
            {{--            <div class="flex items-center justify-between">--}}
            {{--                <a href="index.html" class="text-3xl font-semibold uppercase text-white hover:text-gray-300">Admin</a>--}}
            {{--                <button @click="isOpen = !isOpen" class="text-3xl text-white focus:outline-none">--}}
            {{--                    <svg xmlns="http://www.w3.org/2000/svg"--}}
            {{--                         fill="none"--}}
            {{--                         viewBox="0 0 24 24"--}}
            {{--                         stroke-width="1.5"--}}
            {{--                         stroke="currentColor"--}}
            {{--                         class="h-6 w-6"--}}
            {{--                         x-show="!isOpen">--}}
            {{--                        <path stroke-linecap="round" stroke-linejoin="round"--}}
            {{--                              d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>--}}
            {{--                    </svg>--}}
            {{--                    <i x-show="isOpen" class="fas fa-times"></i>--}}
            {{--                </button>--}}
            {{--            </div>--}}

            {{--            <!-- Dropdown Nav -->--}}
            {{--            <nav :class="isOpen ? 'flex': 'hidden'" class="flex flex-col pt-4">--}}
            {{--                <a href="index.html" class="flex items-center py-2 pl-4 text-white active-nav-link nav-item">--}}
            {{--                    <i class="mr-3 fas fa-tachometer-alt"></i>--}}
            {{--                    Dashboard--}}
            {{--                </a>--}}
            {{--                <a href="blank.html"--}}
            {{--                   class="flex items-center py-2 pl-4 text-white opacity-75 nav-item hover:opacity-100">--}}
            {{--                    <i class="mr-3 fas fa-sticky-note"></i>--}}
            {{--                    Blank Page--}}
            {{--                </a>--}}
            {{--                <a href="tables.html"--}}
            {{--                   class="flex items-center py-2 pl-4 text-white opacity-75 nav-item hover:opacity-100">--}}
            {{--                    <i class="mr-3 fas fa-table"></i>--}}
            {{--                    Tables--}}
            {{--                </a>--}}
            {{--                <a href="forms.html"--}}
            {{--                   class="flex items-center py-2 pl-4 text-white opacity-75 nav-item hover:opacity-100">--}}
            {{--                    <i class="mr-3 fas fa-align-left"></i>--}}
            {{--                    Forms--}}
            {{--                </a>--}}
            {{--                <a href="tabs.html"--}}
            {{--                   class="flex items-center py-2 pl-4 text-white opacity-75 nav-item hover:opacity-100">--}}
            {{--                    <i class="mr-3 fas fa-tablet-alt"></i>--}}
            {{--                    Tabbed Content--}}
            {{--                </a>--}}
            {{--                <a href="calendar.html"--}}
            {{--                   class="flex items-center py-2 pl-4 text-white opacity-75 nav-item hover:opacity-100">--}}
            {{--                    <i class="mr-3 fas fa-calendar"></i>--}}
            {{--                    Calendar--}}
            {{--                </a>--}}
            {{--                <a href="#" class="flex items-center py-2 pl-4 text-white opacity-75 nav-item hover:opacity-100">--}}
            {{--                    <i class="mr-3 fas fa-cogs"></i>--}}
            {{--                    Support--}}
            {{--                </a>--}}
            {{--                <a href="#" class="flex items-center py-2 pl-4 text-white opacity-75 nav-item hover:opacity-100">--}}
            {{--                    <i class="mr-3 fas fa-user"></i>--}}
            {{--                    My Account--}}
            {{--                </a>--}}
            {{--                <a href="#" class="flex items-center py-2 pl-4 text-white opacity-75 nav-item hover:opacity-100">--}}
            {{--                    <i class="mr-3 fas fa-sign-out-alt"></i>--}}
            {{--                    Sign Out--}}
            {{--                </a>--}}
            {{--                <button--}}
            {{--                    class="mt-3 flex w-full items-center justify-center rounded-lg bg-white py-2 font-semibold shadow-lg cta-btn hover:bg-gray-300 hover:shadow-xl">--}}
            {{--                    <i class="mr-3 fas fa-arrow-circle-up"></i> Upgrade to Pro!--}}
            {{--                </button>--}}
            {{--            </nav>--}}
            <!-- <button class="mt-5 flex w-full items-center justify-center rounded-tr-lg rounded-br-lg rounded-bl-lg bg-white py-2 font-semibold shadow-lg cta-btn hover:bg-gray-300 hover:shadow-xl">
                <i class="mr-3 fas fa-plus"></i> New Report
            </button> -->
        </header>

        <div class="flex h-screen w-full flex-col overflow-x-hidden border-t">
            <main class="w-full flex-grow px-3 py-2 font-light">
                <div class="pb-6 text-3xl text-black">
                    @if(Session::has('flashMessage'))
                        <x-alerts.flash-alert :message="Session::get('flashMessage', 'default')" />
                    @endif
                </div>
                <div>
                    @yield('content')
                </div>
            </main>
            <footer class="w-full bg-white p-4 text-center">
                Copyright @ {{ Illuminate\Support\Carbon::now()->year }} MIT Licence
            </footer>
        </div>
    </div>
</div>
<!-- Scripts -->
@livewireScriptConfig
</body>
</html>
