@php
    $sidebarLinks = [
        ['route' => 'client.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['route' => 'client.contacts.index', 'label' => 'Kontak', 'routePattern' => 'client.contacts.*', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
        ['route' => 'client.segments.index', 'label' => 'Segment', 'routePattern' => 'client.segments.*', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
        ['route' => 'client.message-templates.index', 'label' => 'Template', 'routePattern' => 'client.message-templates.*', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['route' => 'client.broadcasts.index', 'label' => 'Campaign', 'routePattern' => 'client.broadcasts.*', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.553-7.827A2 2 0 002.582 10H5.5V4.5A2.5 2.5 0 108 7v.882a1.76 1.76 0 01-3.417.592L2.082 2.082A1.76 1.76 0 013.5.5h15a1.76 1.76 0 011.418.582l-2.5 6A1.76 1.76 0 0117 7.882V19.24a1.76 1.76 0 01-3.417.592l-2.553-7.827A2 2 0 0011.418 10H14.5V4.5A2.5 2.5 0 1012 7v.882z'],
        ['route' => 'client.whatsapp.index', 'label' => 'WhatsApp', 'routePattern' => 'client.whatsapp.*', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
        ['route' => 'client.bantuan', 'label' => 'Bantuan', 'routePattern' => 'client.bantuan', 'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
@endphp
<div x-data="{ sidebarOpen: false }" class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <div x-show="sidebarOpen" x-transition class="fixed inset-0 z-40 bg-black/40 lg:hidden" @click="sidebarOpen = false" aria-hidden="true"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed lg:static inset-y-0 left-0 z-50 w-56 flex flex-col bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transform transition-transform duration-200">
        <div class="h-14 px-4 flex items-center justify-between border-b border-slate-200 dark:border-slate-800 shrink-0">
            <a href="{{ route('client.dashboard') }}" class="text-lg font-semibold text-slate-800 dark:text-slate-100">WA Blast</a>
            <button type="button" @click="sidebarOpen = false" class="lg:hidden p-1.5 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
                <svg class="w-5 h-5" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5">
            @foreach ($sidebarLinks as $link)
                @php
                    $active = isset($link['routePattern']) ? request()->routeIs($link['routePattern']) : request()->routeIs($link['route']);
                @endphp
                <a href="{{ route($link['route']) }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-md text-sm {{ $active ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/></svg>
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>
        <div class="p-3 border-t border-slate-200 dark:border-slate-800 space-y-0.5">
            <p class="px-2.5 py-1 text-xs text-slate-500 dark:text-slate-400 truncate" title="{{ Auth::user()->email }}">{{ Auth::user()->name }}</p>
            <a href="{{ route('profile.edit') }}" class="block px-2.5 py-1.5 text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 rounded-md">Profil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-2.5 py-1.5 text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 rounded-md">Keluar</button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-14 px-4 lg:px-6 flex items-center bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shrink-0">
            <button type="button" @click="sidebarOpen = true" class="lg:hidden p-1.5 -ml-1 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 rounded-md" aria-label="Menu">
                <svg class="w-5 h-5" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            @isset($header)
                <h1 class="ml-2 lg:ml-0 text-base font-semibold text-slate-800 dark:text-slate-100">{{ $header }}</h1>
            @endisset
        </header>
        <main class="flex-1 p-4 lg:p-6 overflow-auto">
            {{ $slot }}
        </main>
    </div>
</div>
