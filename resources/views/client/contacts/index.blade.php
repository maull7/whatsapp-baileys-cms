<x-client-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Contacts
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('client.contacts.import-form') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium">
                    Import CSV/Excel
                </a>
                <a href="{{ route('client.contacts.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium text-sm">
                    Tambah Kontak
                </a>
            </div>
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
                    @if ($contacts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nomor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tags</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($contacts as $contact)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-gray-100">{{ $contact->phone }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $contact->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $contact->email ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $contact->tags ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                <a href="{{ route('client.contacts.edit', $contact) }}" class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 mr-3">Edit</a>
                                                <form action="{{ route('client.contacts.destroy', $contact) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kontak ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $contacts->links() }}</div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Belum ada kontak. <a href="{{ route('client.contacts.create') }}" class="text-emerald-600 hover:underline">Tambah kontak</a> atau <a href="{{ route('client.contacts.import-form') }}" class="text-emerald-600 hover:underline">import dari CSV/Excel</a>.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-client-layout>
