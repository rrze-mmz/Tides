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
<body class="h-screen bg-gray-100 font-sans leading-none antialiased">
<div id="app">
    <header class="fixed top-0 z-10 mt-0 w-full bg-gray-800 p-2 py-4">
        @include('layouts.frontend.top-navigation')
    </header>

    <main class="mx-auto max-h-full min-h-screen pt-12 lg:flex">
        <div class="w-full pb-10 lg:flex-grow">
            @yield('content')
        </div>
    </main>

    <footer class="flex h-10 items-center justify-center bg-gray-800">
        <div class="text-sm text-gray-300 space-x-4 sm:text-base">
            Copyright @ {{ Illuminate\Support\Carbon::now()->year }} MIT Licence
        </div>
    </footer>
</div>
@vite('resources/js/app.js')
@livewireScripts
</body>
</html>
