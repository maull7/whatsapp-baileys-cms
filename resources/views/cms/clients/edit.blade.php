@extends('cms.layout')

@section('title', 'Edit Client')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-slate-800">Edit Client</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-xl">
    <form action="{{ route('cms.clients.update', $client) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Client</label>
                <input type="text" name="name" id="name" value="{{ old('name', $client->name) }}" required
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="api_key" class="block text-sm font-medium text-slate-700 mb-1">API Key</label>
                <input type="text" name="api_key" id="api_key" value="{{ old('api_key', $client->api_key) }}" required
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 font-mono text-sm">
                @error('api_key')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="api_base_url" class="block text-sm font-medium text-slate-700 mb-1">API Base URL</label>
                <input type="url" name="api_base_url" id="api_base_url" value="{{ old('api_base_url', $client->api_base_url) }}"
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('api_base_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">Simpan</button>
            <a href="{{ route('cms.clients.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50">Batal</a>
        </div>
    </form>
</div>
@endsection
