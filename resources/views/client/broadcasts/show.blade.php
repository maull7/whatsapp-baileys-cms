<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Broadcast
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Broadcast</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $broadcast->name ?: 'Broadcast #'.$broadcast->id }}</dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Template</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $broadcast->messageTemplate->name }}</dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 py-1 text-xs rounded-full {{ $broadcast->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : ($broadcast->status === 'processing' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                    {{ ucfirst($broadcast->status) }}
                                </span>
                            </dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jadwal Kirim</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $broadcast->scheduled_at ? $broadcast->scheduled_at->format('d M Y H:i') : 'Langsung' }}</dd>
                        </div>

                        @if ($broadcast->started_at)
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Mulai</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $broadcast->started_at->format('d M Y H:i:s') }}</dd>
                            </div>
                        @endif

                        @if ($broadcast->completed_at)
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Selesai</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $broadcast->completed_at->format('d M Y H:i:s') }}</dd>
                            </div>
                        @endif
                    </dl>

                    <div class="mt-6">
                        <a href="{{ route('client.broadcasts.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">Kembali</a>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Daftar Penerima ({{ $broadcast->recipients->count() }})</h3>
                    @if ($broadcast->recipients->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nomor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu Kirim</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Error</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($broadcast->recipients as $recipient)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono">{{ $recipient->phone }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $recipient->name ?: '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $recipient->status === 'sent' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : ($recipient->status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                                    {{ ucfirst($recipient->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $recipient->sent_at ? $recipient->sent_at->format('H:i:s') : '-' }}</td>
                                            <td class="px-6 py-4 text-sm text-red-600 dark:text-red-400">{{ $recipient->error ? \Illuminate\Support\Str::limit($recipient->error, 50) : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $broadcast->recipients->where('status', 'sent')->count() }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Terkirim</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $broadcast->recipients->where('status', 'failed')->count() }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Gagal</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $broadcast->recipients->where('status', 'pending')->count() }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Pending</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada penerima.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-client-layout>
