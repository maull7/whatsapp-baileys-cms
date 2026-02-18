@extends('cms.layout')

@section('title', 'Tambah User')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-slate-800">Tambah User</h1>
    <p class="text-slate-600 mt-1">Pilih client = user client (bisa login & kirim pesan). Kosongkan = admin CMS.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-xl">
    <form action="{{ route('cms.users.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>
            <div>
                <label for="client_id" class="block text-sm font-medium text-slate-700 mb-1">Client (kosongkan = admin)</label>
                <select name="client_id" id="client_id"
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">-- Admin (akses full CMS) --</option>
                    @foreach ($clients as $c)
                        <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('client_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">Simpan</button>
            <a href="{{ route('cms.users.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50">Batal</a>
        </div>
    </form>
</div>
@endsection
