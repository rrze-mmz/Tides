<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="h-screen font-sans antialiased leading-none bg-gray-100">

<!--Nav-->
<nav class="fixed top-0 z-10 p-2 mt-0 w-full bg-gray-800">
    <div class="container flex flex-wrap items-center mx-auto">
        <div class="flex justify-center w-full font-extrabold text-white md:w-1/2 md:justify-start">
            <a class="text-white no-underline hover:text-white hover:no-underline" href="#">
                <span class="pl-2 text-2xl"><i class="em em-grinning"></i> Brand McBrandface</span>
            </a>
        </div>
        <div class="flex justify-between content-center pt-2 w-full md:w-1/2 md:justify-end">
            <ul class="flex flex-1 justify-between items-center list-reset md:flex-none">
                <li class="mr-3">
                    <a class="inline-block py-2 px-4 text-white no-underline" href="#">Active</a>
                </li>
                <li class="mr-3">
                    <a class="inline-block py-2 px-4 text-gray-600 no-underline hover:text-gray-200 hover:text-underline" href="#">link</a>
                </li>
                <li class="mr-3">
                    <a class="inline-block py-2 px-4 text-gray-600 no-underline hover:text-gray-200 hover:text-underline" href="#">link</a>
                </li>
                <li class="mr-3">
                    <a class="inline-block py-2 px-4 text-gray-600 no-underline hover:text-gray-200 hover:text-underline" href="#">link</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="flex flex-col">


    <div class="flex justify-center items-center min-h-screen">
        <nav class="flex sticky flex-col justify-around h-full">
            <div>
                <h1 class="mb-6 text-6xl font-light tracking-wider text-center text-gray-600 sm:mb-8">
                    {{ config('app.name', 'Laravel') }}
                </h1>
                @if(Route::has('login'))
                    <div class="flex flex-col justify-center items-center space-y-2 sm:flex-row sm:flex-wrap sm:space-x-8 sm:space-y-0">
                        @auth
                            <a href="{{ url('/home') }}" class="text-sm font-normal text-teal-800 no-underline uppercase hover:underline">{{ __('Home') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-normal text-teal-800 no-underline uppercase hover:underline">{{ __('Login') }}</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-sm font-normal text-teal-800 no-underline uppercase hover:underline">{{ __('Register') }}</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </nav>
    </div>
</div>
</body>
</html>
