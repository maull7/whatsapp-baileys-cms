<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Template Pesan
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-xl">
                @if (session('success'))
                    <div
                        class="mb-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('client.message-templates.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama
                                Template</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="body" class="block text-sm font-medium text-slate-700 mb-1">Isi
                                Pesan</label>
                            <textarea name="body" id="body" rows="5" required
                                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('body', 'Halo ') }}</textarea>
                            @error('body')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="submit"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">Simpan</button>
                        <a href="{{ route('client.message-templates.index') }}"
                            class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-client-layout>
