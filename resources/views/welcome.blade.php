<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        </style>
    </head>
    <body class="bg-gray-200">
            <div class="flex justify-center items-center h-screen text-6xl text-purple-700">
                Tides
            </div>
    </body>
</html>
