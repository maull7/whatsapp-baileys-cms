<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Tambah Segment</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 text-red-800 dark:text-red-200 text-sm">
                        <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif
                <form action="{{ route('client.segments.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Segment *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: VIP, Jakarta"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                            <textarea name="description" id="description" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">{{ old('description') }}</textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">Simpan</button>
                            <a href="{{ route('client.segments.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-client-layout>
