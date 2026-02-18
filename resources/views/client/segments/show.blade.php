<x-client-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $segment->name }}</h2>
            <a href="{{ route('client.segments.add-contacts', $segment) }}"
                class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium text-sm">
                Tambah Kontak
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

            @if ($segment->description)
                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">{{ $segment->description }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Kontak di segment ({{ $segment->contacts->count() }})</h3>
                    @if ($segment->contacts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nomor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($segment->contacts as $contact)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-gray-100">{{ $contact->phone }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $contact->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                <form action="{{ route('client.segments.detach-contact', [$segment, $contact]) }}" method="POST" class="inline" onsubmit="return confirm('Keluarkan kontak dari segment?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline dark:text-red-400">Keluarkan</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Belum ada kontak. <a href="{{ route('client.segments.add-contacts', $segment) }}" class="text-emerald-600 hover:underline">Tambah kontak ke segment</a>.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-client-layout>
