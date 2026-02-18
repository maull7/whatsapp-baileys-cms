@extends('cms.layout')

@section('title', 'Tambah Template Pesan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-slate-800">Tambah Template Pesan</h1>
    <p class="text-slate-600 mt-1">Gunaka untuk variabel dinamis.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-xl">
    <form action="{{ route('cms.templates.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="client_id" class="block text-sm font-medium text-slate-700 mb-1">Client</label>
                <select name="client_id" id="client_id" required
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Pilih client</option>
                    @foreach ($clients as $c)
                        <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('client_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Template</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="body" class="block text-sm font-medium text-slate-700 mb-1">Isi Pesan</label>
                <textarea name="body" id="body" rows="5" required
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('body', 'Halo ') }}</textarea>
                @error('body')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">Simpan</button>
            <a href="{{ route('cms.templates.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50">Batal</a>
        </div>
    </form>
</div>
@endsection
