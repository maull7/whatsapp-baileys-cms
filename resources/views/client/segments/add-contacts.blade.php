<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Tambah Kontak ke "{{ $segment->name }}"</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Kontak di bawah ini belum ada di segment. Klik "Tambah" untuk memasukkan ke segment.
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($contacts->count() > 0)
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
                                    @foreach ($contacts as $contact)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-gray-100">{{ $contact->phone }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $contact->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                <form action="{{ route('client.segments.attach-contact', $segment) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="contact_id" value="{{ $contact->id }}">
                                                    <button type="submit" class="text-emerald-600 hover:underline dark:text-emerald-400 font-medium">Tambah</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $contacts->links() }}</div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Semua kontak sudah ada di segment ini, atau belum ada kontak. <a href="{{ route('client.contacts.index') }}" class="text-emerald-600 hover:underline">Kelola kontak</a>.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-client-layout>
