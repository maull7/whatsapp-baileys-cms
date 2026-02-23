<x-client-layout>
    <x-slot name="header">Kontak</x-slot>

    <div class="max-w-4xl space-y-4">
        @if (session('success'))
            <div class="px-4 py-3 rounded-lg bg-green-50 dark:bg-green-950/50 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-slate-600 dark:text-slate-400">
                Kelola daftar kontak yang akan dipakai untuk campaign.
            </p>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('client.contacts.import-form') }}"
                    class="inline-flex items-center px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                    Import CSV/Excel
                </a>
                <a href="{{ route('client.contacts.create') }}"
                    class="inline-flex items-center px-3 py-2 rounded-lg bg-slate-800 dark:bg-slate-100 text-sm font-medium text-white dark:text-slate-900 hover:bg-slate-700 dark:hover:bg-slate-200">
                    Tambah Kontak
                </a>
            </div>
        </div>

        <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 overflow-hidden">
            <div class="p-4">
                @if ($contacts->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-700">
                                    <th class="px-4 py-2.5 text-left font-medium text-slate-500 dark:text-slate-400">Nomor</th>
                                    <th class="px-4 py-2.5 text-left font-medium text-slate-500 dark:text-slate-400">Nama</th>
                                    <th class="px-4 py-2.5 text-left font-medium text-slate-500 dark:text-slate-400">Email</th>
                                    <th class="px-4 py-2.5 text-left font-medium text-slate-500 dark:text-slate-400">Tags</th>
                                    <th class="px-4 py-2.5 text-right font-medium text-slate-500 dark:text-slate-400">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($contacts as $contact)
                                    <tr>
                                        <td class="px-4 py-2.5 whitespace-nowrap font-mono text-slate-800 dark:text-slate-100 text-sm">{{ $contact->phone }}</td>
                                        <td class="px-4 py-2.5 whitespace-nowrap text-slate-800 dark:text-slate-100 text-sm">{{ $contact->name ?? '-' }}</td>
                                        <td class="px-4 py-2.5 whitespace-nowrap text-slate-600 dark:text-slate-400 text-sm">{{ $contact->email ?? '-' }}</td>
                                        <td class="px-4 py-2.5 whitespace-nowrap text-slate-600 dark:text-slate-400 text-sm">{{ $contact->tags ?? '-' }}</td>
                                        <td class="px-4 py-2.5 whitespace-nowrap text-right text-sm">
                                            <a href="{{ route('client.contacts.edit', $contact) }}" class="text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 mr-3">Edit</a>
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
                    <div class="mt-4">
                        {{ $contacts->links() }}
                    </div>
                @else
                    <p class="text-slate-500 dark:text-slate-400 text-sm">
                        Belum ada kontak.
                        <a href="{{ route('client.contacts.create') }}" class="text-slate-800 dark:text-slate-100 font-medium hover:underline">Tambah kontak</a>
                        atau
                        <a href="{{ route('client.contacts.import-form') }}" class="text-slate-800 dark:text-slate-100 font-medium hover:underline">import dari CSV/Excel</a>.
                    </p>
                @endif
            </div>
        </section>
    </div>
</x-client-layout>
