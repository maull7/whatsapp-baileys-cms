<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Import Kontak</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 px-4 py-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 text-blue-800 dark:text-blue-200 text-sm">
                Format file: CSV atau Excel dengan kolom <strong>phone</strong> dan <strong>nama</strong> (opsional). Maksimal 500 baris. Nomor duplikat akan di-skip (update data).
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 text-red-800 dark:text-red-200 text-sm">
                        @foreach ($errors->all() as $e)
                            <p>{{ $e }}</p>
                        @endforeach
                    </div>
                @endif
                <form action="{{ route('client.contacts.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">File CSV/Excel</label>
                        <input type="file" name="file" id="file" accept=".csv,.txt,.xls,.xlsx" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">Import</button>
                        <a href="{{ route('client.contacts.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-client-layout>
