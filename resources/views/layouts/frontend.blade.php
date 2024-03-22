<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="icon" href="{{ URL::asset('/css/favicon.ico') }}" type="image/x-icon" />
    @vite('resources/css/app.css')
    {{--    @livewireStyles--}}
</head>
<body x-cloak
      x-data="{darkMode:  $persist(false)}"
      :class="{'dark': darkMode === true }"
      class="antialiased">
<div id="app">
    <header class="fixed top-0 z-10 mt-0 w-full p-2 py-4
            bg-gray-100 dark:bg-sky-950 text-dark
                dark:text-gray-200">
        @include('layouts.frontend.top-navigation')
    </header>
    <main class="mx-auto max-h-full min-h-screen pt-12 lg:flex bg-gray-200 dark:bg-slate-900">
        <div class="w-full pb-10 lg:flex-grow">
            @yield('content')
        </div>
    </main>


    <footer class="bg-gray-100 dark:bg-sky-950">
        <div
            class="flex w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between text-dark
                dark:text-white">
            <div class="text-sm sm:text-center grow">
                Copyright @ {{ Illuminate\Support\Carbon::now()->year }} MIT Licence [ v. {{ getCurrentGitBranch() }}]
            </div>

            <div>
                @include('layouts.frontend.bottom-navigation')
            </div>

        </div>
    </footer>
</div>
@vite('resources/js/app.js')
{{--@livewireScriptConfig--}}
</body>
</html>
