<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="icon" href="{{ URL::asset('/css/favicon.ico') }}" type="image/x-icon"/>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="h-screen font-sans antialiased leading-none bg-gray-100 ">
<div id="app">
    <header class="fixed top-0 z-10 p-2 py-4 mt-0 w-full bg-gray-800 ">
        @include('layouts.frontend.top-navigation')
    </header>

    <main class="pt-12 mx-auto lg:flex min-h-screen max-h-full">
        <div class=" w-full lg:flex-grow pb-10 ">
            @yield('content')
        </div>
    </main>

    <footer class="flex bg-gray-800 h-10 justify-center items-center">
        <div class="space-x-4 text-sm text-gray-300 sm:text-base">
            Copyright @ {{ Illuminate\Support\Carbon::now()->year }} MIT Licence
        </div>
    </footer>
</div>
@vite('resources/js/app.js')
@livewireScripts
</body>
</html>
