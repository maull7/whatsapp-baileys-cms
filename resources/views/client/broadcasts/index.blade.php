<x-client-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Campaigns</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Broadcast logs & riwayat pengiriman</p>
            </div>
            <a href="{{ route('client.broadcasts.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Buat Campaign
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($broadcasts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Template</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Target</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Terkirim / Gagal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Waktu</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($broadcasts as $broadcast)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $broadcast->name ?: 'Campaign #'.$broadcast->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $broadcast->messageTemplate->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $broadcast->segment ? $broadcast->segment->name : 'File' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $broadcast->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : ($broadcast->status === 'processing' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">{{ ucfirst($broadcast->status) }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $broadcast->sent_count ?? 0 }} / {{ $broadcast->failed_count ?? 0 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $broadcast->created_at->format('d M Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                <a href="{{ route('client.broadcasts.show', $broadcast) }}" class="text-emerald-600 hover:underline dark:text-emerald-400">Log</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $broadcasts->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Belum ada campaign. <a href="{{ route('client.broadcasts.create') }}" class="text-emerald-600 hover:underline">Buat campaign pertama</a>.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-client-layout>
