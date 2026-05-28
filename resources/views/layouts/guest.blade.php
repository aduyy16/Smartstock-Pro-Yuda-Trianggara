<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SmartStock Pro') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-gray-100 via-indigo-50/30 to-purple-50/20 dark:from-gray-900 dark:via-gray-900 dark:to-indigo-950/20">
            <div class="mb-2">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-4 px-6 py-6 bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none overflow-hidden sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-gray-400 dark:text-gray-600">&copy; {{ date('Y') }} SmartStock Pro</p>
        </div>
    </body>
</html>
