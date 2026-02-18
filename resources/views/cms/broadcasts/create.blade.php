@extends('cms.layout')

@section('title', 'Buat Broadcast')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-slate-800">Buat Broadcast</h1>
    <p class="text-slate-600 mt-1">Pilih client, template, upload CSV penerima. Kosongkan jadwal = kirim sekarang (via queue).</p>
    <div class="mt-3 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
        <strong>Kirim sekarang:</strong> kosongkan jadwal → pesan dikirim langsung.<br>
        <strong>Jadwal:</strong> isi tanggal/waktu → broadcast dikirim saat waktunya. Jalankan <code class="bg-emerald-100 px-1 rounded">php artisan schedule:work</code> (atau cron <code class="bg-emerald-100 px-1 rounded">* * * * * php artisan schedule:run</code>). Log: <code class="bg-emerald-100 px-1 rounded">storage/logs/laravel.log</code>.
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-xl">
    @if ($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mt-1 list-disc list-inside">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('cms.broadcasts.store') }}" method="POST" enctype="multipart/form-data">
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
                <label for="message_template_id" class="block text-sm font-medium text-slate-700 mb-1">Template Pesan</label>
                <select name="message_template_id" id="message_template_id" required
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Pilih template</option>
                    @foreach ($templates as $t)
                        <option value="{{ $t->id }}" data-client="{{ $t->client_id }}" {{ old('message_template_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->name }} ({{ $t->client->name }})
                        </option>
                    @endforeach
                </select>
                @error('message_template_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Broadcast (opsional)</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Blast Promo Januari"
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="scheduled_at" class="block text-sm font-medium text-slate-700 mb-1">Jadwal kirim (kosong = kirim sekarang)</label>
                <input type="datetime-local" name="scheduled_at" id="scheduled_at" value="{{ old('scheduled_at') }}"
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                    min="{{ now()->addMinute()->format('Y-m-d\TH:i') }}">
                @error('scheduled_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="recipients_file" class="block text-sm font-medium text-slate-700 mb-1">File CSV/Excel Penerima</label>
                <span class="text-sm block mb-1">
                    <a href="{{ route('cms.broadcasts.template') }}" class="text-emerald-600 hover:underline">Download template CSV</a>
                    <span class="text-slate-400 mx-1">|</span>
                    <a href="{{ route('cms.broadcasts.template-excel') }}" class="text-emerald-600 hover:underline">Download template Excel</a>
                </span>
                <input type="file" name="recipients_file" id="recipients_file" accept=".csv,.txt,.xls" required
                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('recipients_file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-slate-500">Kolom: phone, nama, pesan (nama dan pesan opsional). Maksimal <strong>20 baris</strong> per file. Satu file tidak boleh berisi <strong>nomor yang sama</strong>. Di Excel: format kolom phone sebagai Teks supaya nomor tidak berubah.</p>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">Simpan & Kirim</button>
            <a href="{{ route('cms.broadcasts.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50">Batal</a>
        </div>
    </form>
</div>
@endsection
