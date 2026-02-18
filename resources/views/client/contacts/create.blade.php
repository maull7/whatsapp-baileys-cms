<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Tambah Kontak</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 text-red-800 dark:text-red-200 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('client.contacts.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor HP *</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                placeholder="6285123456789"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tags</label>
                            <input type="text" name="tags" id="tags" value="{{ old('tags') }}" placeholder="vip, jakarta"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">Simpan</button>
                            <a href="{{ route('client.contacts.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-client-layout>
