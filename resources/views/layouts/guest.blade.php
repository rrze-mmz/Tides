<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Styles -->

    <!-- Scripts -->
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body x-cloak
      x-data="{darkMode:  $persist(false)}"
      :class="{'dark': darkMode === true }"
      class="antialiased">
<div id="app" class="font-sans bg-slate-500 antialiased">
    {{ $slot }}
</div>
@vite('resources/js/app.js')
@livewireScriptConfig
</body>
</html>
