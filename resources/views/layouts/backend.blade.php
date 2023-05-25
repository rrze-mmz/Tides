<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">


    @livewireStyles
    @trixassets
</head>
<body id="app">
<div class="flex bg-gray-100">
    <aside class="relative bg-sidebar h-screen w-1/12 hidden sm:block shadow-xl">
        <div class="p-6 text-center align-center">
            <a href="{{route('home')}}"
               class="text-white text-3xl font-semibold hover:text-gray-300">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>
        <nav class="text-white text-base font-semibold pt-3">
            @include('backend.dashboard._sidebar-navigation')
        </nav>
        <a href="#"
           class="absolute w-full upgrade-btn bottom-0 active-nav-link text-white flex items-center justify-center py-4">
            <x-heroicon-o-chevron-double-left class="w-6 h-6"/>
        </a>
    </aside>

    <div class="relative w-full flex flex-col h-screen overflow-y-hidden">
        <!-- Desktop Header -->
        <header class="flex w-full justify-end items-center bg-white px-6 hidden sm:flex">
            <div class="w-1/2">
                @include('backend.search._searchbar')
            </div>
            <div x-data="{ isOpen: false }" class="relative flex justify-end">
                <x-heroicon-o-user @click="isOpen = !isOpen"
                                   class="relative z-10 w-12 h-12 rounded-full overflow-hidden border-2 border-gray-400
                                            hover:border-gray-300 focus:border-gray-300 focus:outline-none"/>
                </button>
                <button x-show="isOpen"
                        @click="isOpen = false"
                        class="h-full w-full fixed inset-0 cursor-default">
                </button>
                <div x-show="isOpen"
                     class="absolute w-48 bg-white px-10 items-center align-middle rounded-lg shadow-lg py-2 mt-16">
                    <a href="#" class="block px-4 py-2 account-link hover:text-white">Settings</a>
                    <a href="#" class="block px-4 py-2 account-link hover:text-white">Notifications</a>
                    <a href="{{ route('logout') }}"
                       class="block px-4 py-2 account-link hover:text-white"
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
        <header x-data="{ isOpen: false }" class="w-full bg-sidebar py-5 px-6 sm:hidden">
            {{--            <div class="flex items-center justify-between">--}}
            {{--                <a href="index.html" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">Admin</a>--}}
            {{--                <button @click="isOpen = !isOpen" class="text-white text-3xl focus:outline-none">--}}
            {{--                    <svg xmlns="http://www.w3.org/2000/svg"--}}
            {{--                         fill="none"--}}
            {{--                         viewBox="0 0 24 24"--}}
            {{--                         stroke-width="1.5"--}}
            {{--                         stroke="currentColor"--}}
            {{--                         class="w-6 h-6"--}}
            {{--                         x-show="!isOpen">--}}
            {{--                        <path stroke-linecap="round" stroke-linejoin="round"--}}
            {{--                              d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>--}}
            {{--                    </svg>--}}
            {{--                    <i x-show="isOpen" class="fas fa-times"></i>--}}
            {{--                </button>--}}
            {{--            </div>--}}

            {{--            <!-- Dropdown Nav -->--}}
            {{--            <nav :class="isOpen ? 'flex': 'hidden'" class="flex flex-col pt-4">--}}
            {{--                <a href="index.html" class="flex items-center active-nav-link text-white py-2 pl-4 nav-item">--}}
            {{--                    <i class="fas fa-tachometer-alt mr-3"></i>--}}
            {{--                    Dashboard--}}
            {{--                </a>--}}
            {{--                <a href="blank.html"--}}
            {{--                   class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">--}}
            {{--                    <i class="fas fa-sticky-note mr-3"></i>--}}
            {{--                    Blank Page--}}
            {{--                </a>--}}
            {{--                <a href="tables.html"--}}
            {{--                   class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">--}}
            {{--                    <i class="fas fa-table mr-3"></i>--}}
            {{--                    Tables--}}
            {{--                </a>--}}
            {{--                <a href="forms.html"--}}
            {{--                   class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">--}}
            {{--                    <i class="fas fa-align-left mr-3"></i>--}}
            {{--                    Forms--}}
            {{--                </a>--}}
            {{--                <a href="tabs.html"--}}
            {{--                   class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">--}}
            {{--                    <i class="fas fa-tablet-alt mr-3"></i>--}}
            {{--                    Tabbed Content--}}
            {{--                </a>--}}
            {{--                <a href="calendar.html"--}}
            {{--                   class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">--}}
            {{--                    <i class="fas fa-calendar mr-3"></i>--}}
            {{--                    Calendar--}}
            {{--                </a>--}}
            {{--                <a href="#" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">--}}
            {{--                    <i class="fas fa-cogs mr-3"></i>--}}
            {{--                    Support--}}
            {{--                </a>--}}
            {{--                <a href="#" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">--}}
            {{--                    <i class="fas fa-user mr-3"></i>--}}
            {{--                    My Account--}}
            {{--                </a>--}}
            {{--                <a href="#" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">--}}
            {{--                    <i class="fas fa-sign-out-alt mr-3"></i>--}}
            {{--                    Sign Out--}}
            {{--                </a>--}}
            {{--                <button--}}
            {{--                    class="w-full bg-white cta-btn font-semibold py-2 mt-3 rounded-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">--}}
            {{--                    <i class="fas fa-arrow-circle-up mr-3"></i> Upgrade to Pro!--}}
            {{--                </button>--}}
            {{--            </nav>--}}
            <!-- <button class="w-full bg-white cta-btn font-semibold py-2 mt-5 rounded-br-lg rounded-bl-lg rounded-tr-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
                <i class="fas fa-plus mr-3"></i> New Report
            </button> -->
        </header>

        <div class="w-full h-screen overflow-x-hidden border-t flex flex-col">
            <main class="w-full flex-grow py-2 px-3 font-light">
                <div class="text-3xl text-black pb-6">
                    @if(Session::has('flashMessage'))
                        <x-alerts.flash-alert :message="Session::get('flashMessage', 'default')"/>
                    @endif
                </div>
                <div>
                    @yield('content')
                </div>
            </main>
            <footer class="w-full bg-white text-center p-4">
                Copyright @ {{ Illuminate\Support\Carbon::now()->year }} MIT Licence
            </footer>
        </div>
    </div>
</div>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>
@livewireScripts
</body>
</html>
