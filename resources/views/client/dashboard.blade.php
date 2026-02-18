<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-lg shadow-lg p-6 text-white">
                <h3 class="text-2xl font-bold mb-2">Selamat Datang, {{ $user->name }}!</h3>
                <p class="text-emerald-100">Client: {{ $client->name }}</p>
                <div class="mt-4 p-4 bg-white/10 rounded-lg">
                    <p class="text-sm font-semibold mb-1">API Key Anda:</p>
                    <code class="text-xs bg-gray-900/30 px-3 py-2 rounded block break-all">{{ $user->api_key }}</code>
                </div>
            </div>

            <div class="mb-6 px-4 py-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200">
                <p class="font-semibold mb-1">⚠️ Campaign Terjadwal</p>
                <p class="text-sm">Campaign terjadwal akan terkirim otomatis sesuai jam tanpa perlu reload halaman. Pastikan scheduler Laravel berjalan:</p>
                <ul class="mt-2 text-sm list-disc list-inside space-y-1">
                    <li><strong>Development:</strong> Jalankan <code class="bg-amber-100 dark:bg-amber-900/50 px-1 rounded">php artisan schedule:work</code> di terminal terpisah</li>
                    <li><strong>Production:</strong> Setup cron: <code class="bg-amber-100 dark:bg-amber-900/50 px-1 rounded">* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1</code></li>
                </ul>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                        Total Campaign
                                    </dt>
                                    <dd class="text-3xl font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $totalBroadcasts }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <a href="{{ route('client.broadcasts.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Campaign
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Broadcast Terbaru</h3>
                    @if ($recentBroadcasts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jadwal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($recentBroadcasts as $broadcast)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $broadcast->name ?: 'Broadcast #'.$broadcast->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $broadcast->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : ($broadcast->status === 'processing' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                                    {{ ucfirst($broadcast->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $broadcast->scheduled_at ? $broadcast->scheduled_at->format('d M Y H:i') : '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('client.broadcasts.show', $broadcast) }}" class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Belum ada broadcast.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-client-layout>
