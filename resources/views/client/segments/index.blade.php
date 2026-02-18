<x-client-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Segments</h2>
            <a href="{{ route('client.segments.create') }}"
                class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium text-sm">
                Tambah Segment
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
                <div class="p-6">
                    @if ($segments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Jumlah Kontak</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Deskripsi</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($segments as $segment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $segment->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $segment->contacts_count }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($segment->description, 50) ?: '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                <a href="{{ route('client.segments.show', $segment) }}" class="text-blue-600 hover:underline dark:text-blue-400 mr-3">Lihat</a>
                                                <a href="{{ route('client.segments.add-contacts', $segment) }}" class="text-emerald-600 hover:underline dark:text-emerald-400 mr-3">Tambah Kontak</a>
                                                <a href="{{ route('client.segments.edit', $segment) }}" class="text-gray-600 hover:underline dark:text-gray-400 mr-3">Edit</a>
                                                <form action="{{ route('client.segments.destroy', $segment) }}" method="POST" class="inline" onsubmit="return confirm('Hapus segment ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline dark:text-red-400">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $segments->links() }}</div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Belum ada segment. Segment dipakai untuk mengelompokkan kontak (mis. VIP, Wilayah) dan bisa dipilih saat buat campaign.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-client-layout>
