<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-slate-100 via-emerald-50/30 to-slate-100 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800">
            <a href="/" class="flex items-center gap-2 mb-2">
                <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">WA Blast</span>
                <span class="text-sm text-gray-500 dark:text-gray-400 hidden sm:inline">WhatsApp Automation</span>
            </a>

            <div class="w-full sm:max-w-md mt-4 px-6 py-8 bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
