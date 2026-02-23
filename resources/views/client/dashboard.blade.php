<x-client-layout>
    <x-slot name="header">Dashboard</x-slot>

    <div class="max-w-4xl space-y-5">
        @if (session('success'))
            <div class="px-4 py-3 rounded-lg bg-green-50 dark:bg-green-950/50 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <section class="p-5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
            <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100">Selamat datang, {{ $user->name }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $client->name }}</p>
            <div class="mt-4 p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">API Key</p>
                <code class="text-xs text-slate-700 dark:text-slate-300 break-all font-mono">{{ $user->api_key }}</code>
            </div>
        </section>

        <a href="{{ route('client.bantuan') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/50 text-slate-700 dark:text-slate-300 text-sm transition-colors">
            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
            <span>Panduan penggunaan aplikasi</span>
        </a>

        <section class="px-4 py-3 rounded-lg bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50">
            <p class="font-medium text-amber-800 dark:text-amber-200 text-sm">Campaign terjadwal</p>
            <p class="text-sm text-amber-700 dark:text-amber-300 mt-0.5">Campaign terkirim otomatis sesuai jadwal. Pastikan scheduler berjalan:</p>
            <ul class="mt-2 text-sm text-amber-700 dark:text-amber-300 space-y-0.5 list-disc list-inside">
                <li>Development: <code class="bg-amber-100 dark:bg-amber-900/50 px-1 rounded text-xs">php artisan schedule:work</code></li>
                <li>Production: cron <code class="bg-amber-100 dark:bg-amber-900/50 px-1 rounded text-xs">* * * * * cd /path && php artisan schedule:run</code></li>
            </ul>
        </section>

        <div class="flex flex-wrap items-center gap-4">
            <div class="px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 min-w-[140px]">
                <p class="text-xs text-slate-500 dark:text-slate-400">Total Campaign</p>
                <p class="text-xl font-semibold text-slate-800 dark:text-slate-100 mt-0.5">{{ $totalBroadcasts }}</p>
            </div>
            <a href="{{ route('client.broadcasts.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-slate-800 dark:bg-slate-100 text-white dark:text-slate-900 text-sm font-medium hover:bg-slate-700 dark:hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Campaign
            </a>
        </div>

        <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Broadcast terbaru</h3>
            </div>
            <div class="p-4">
                @if ($recentBroadcasts->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-700">
                                    <th class="px-4 py-2.5 text-left font-medium text-slate-500 dark:text-slate-400">Nama</th>
                                    <th class="px-4 py-2.5 text-left font-medium text-slate-500 dark:text-slate-400">Status</th>
                                    <th class="px-4 py-2.5 text-left font-medium text-slate-500 dark:text-slate-400">Jadwal</th>
                                    <th class="px-4 py-2.5 text-left font-medium text-slate-500 dark:text-slate-400">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($recentBroadcasts as $broadcast)
                                    <tr>
                                        <td class="px-4 py-2.5 font-medium text-slate-800 dark:text-slate-100">{{ $broadcast->name ?: 'Broadcast #'.$broadcast->id }}</td>
                                        <td class="px-4 py-2.5">
                                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded {{ $broadcast->status === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : ($broadcast->status === 'processing' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300') }}">
                                                {{ ucfirst($broadcast->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400">{{ $broadcast->scheduled_at ? $broadcast->scheduled_at->format('d M Y H:i') : '–' }}</td>
                                        <td class="px-4 py-2.5">
                                            <a href="{{ route('client.broadcasts.show', $broadcast) }}" class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 font-medium">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-slate-500 dark:text-slate-400 text-sm py-2">Belum ada broadcast.</p>
                @endif
            </div>
        </section>
    </div>
</x-client-layout>
