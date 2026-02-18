<x-admin-layout>
    <x-slot name="header">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-slate-800">Client (API Key)</h1>
            <p class="text-slate-600 mt-1">Daftar client yang bisa mengakses backend WhatsApp (Node.js).</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-slate-800">Client (API Key)</h1>
                <a href="{{ route('admin.clients.create') }}"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">
                    Tambah Client
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase">API Key</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase">Base URL</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($clients as $client)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-sm text-slate-800">{{ $client->name }}</td>
                                <td class="px-4 py-3 text-sm font-mono text-slate-600">
                                    {{ Str::limit($client->api_key, 20) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $client->api_base_url ?: '-' }}</td>
                                <td class="px-4 py-3 text-right text-sm">

                                    <a href="{{ route('admin.clients.edit', $client) }}"
                                        class="text-emerald-600 hover:underline mr-3">Edit</a>
                                    <form action="{{ route('admin.clients.destroy', $client) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Hapus client ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-500">Belum ada client. Tambah
                                    client
                                    untuk koneksi ke backend WhatsApp (Node.js).</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($clients->hasPages())
                    <div class="px-4 py-3 border-t border-slate-200">
                        {{ $clients->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-admin-layout>
