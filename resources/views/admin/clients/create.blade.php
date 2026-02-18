<x-admin-layout>
    <x-slot name="header">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-slate-800">Tambah Client</h1>
            <p class="text-slate-600 mt-1">Setiap client punya API key untuk proyek backend WhatsApp (Node.js) di luar
                direktori ini.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-full">
                <form action="{{ route('admin.clients.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama
                                Client</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="api_key" class="block text-sm font-medium text-slate-700 mb-1">API Key
                                (kosongkan untuk
                                auto-generate)</label>
                            <input type="text" name="api_key" id="api_key" value="{{ old('api_key') }}"
                                placeholder="Opsional"
                                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 font-mono text-sm">
                            @error('api_key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="api_base_url" class="block text-sm font-medium text-slate-700 mb-1">API Base URL
                                (backend
                                Node.js)</label>
                            <input type="url" name="api_base_url" id="api_base_url"
                                value="{{ old('api_base_url') }}" placeholder="https://api.example.com"
                                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('api_base_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-slate-500">Kosongkan untuk memakai WHATSAPP_API_BASE_URL di .env
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="submit"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">Simpan</button>
                        <a href="{{ route('admin.clients.index') }}"
                            class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
