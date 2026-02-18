<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - {{ auth()->user()->client->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700" rel="stylesheet" />
    <style> body { font-family: 'DM Sans', ui-sans-serif, system-ui, sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen">
    <nav class="bg-emerald-700 text-white shadow">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between h-14 items-center">
                <div class="flex items-center gap-6">
                    <a href="{{ route('client.dashboard') }}" class="font-semibold text-lg">{{ auth()->user()->client->name }}</a>
                    <a href="{{ route('client.dashboard') }}" class="px-3 py-2 rounded-md {{ request()->routeIs('client.dashboard') ? 'bg-emerald-800' : 'hover:bg-emerald-600' }}">Dashboard</a>
                    <a href="{{ route('client.whatsapp') }}" class="px-3 py-2 rounded-md {{ request()->routeIs('client.whatsapp*') ? 'bg-emerald-800' : 'hover:bg-emerald-600' }}">WhatsApp</a>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-emerald-100">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-md hover:bg-emerald-600 text-sm">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-800 border border-red-200">
                {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>
