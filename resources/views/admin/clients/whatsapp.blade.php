@extends('cms.layout')

@section('title', 'WhatsApp - ' . $client->name)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-800">Koneksi WhatsApp</h1>
        <p class="text-slate-600 mt-1">{{ $client->name }}</p>
    </div>
    <a href="{{ route('cms.clients.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50">Kembali ke Client</a>
</div>

@if ($connected)
    <div class="mb-4 px-4 py-3 rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-2">
        <span class="inline-block w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
        Terhubung ke WhatsApp
        @if (!empty($status))
            <span class="text-sm">({{ $status }})</span>
        @endif
    </div>
@else
    <div class="mb-4 px-4 py-3 rounded-lg bg-amber-100 text-amber-800 border border-amber-200 flex items-center gap-2">
        <span class="inline-block w-3 h-3 rounded-full bg-amber-500"></span>
        Belum terhubung. Scan QR code di bawah atau klik Reconnect.
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-lg">
    <div class="flex flex-wrap gap-3 mb-6">
        <form action="{{ route('cms.clients.whatsapp.reconnect', $client) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                Reconnect
            </button>
        </form>
        <form action="{{ route('cms.clients.whatsapp.logout', $client) }}" method="POST" class="inline" onsubmit="return confirm('Logout dari WhatsApp? Perlu scan QR lagi untuk masuk.');">
            @csrf
            <button type="submit" class="px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 font-medium">
                Logout
            </button>
        </form>
    </div>

    @if ($qrImageUrl)
        <div class="border border-slate-200 rounded-lg p-4 bg-slate-50 inline-block">
            <p class="text-sm text-slate-600 mb-3">Scan dengan WhatsApp di HP:</p>
            <img src="{{ $qrImageUrl }}" alt="QR Code WhatsApp" class="w-64 h-64 object-contain mx-auto" />
        </div>
    @elseif ($useQrProxy ?? false)
        <div class="border border-slate-200 rounded-lg p-4 bg-slate-50 inline-block">
            <p class="text-sm text-slate-600 mb-3">Scan dengan WhatsApp di HP:</p>
            <img src="{{ route('cms.clients.whatsapp.qr-image', $client) }}?t={{ time() }}" alt="QR Code WhatsApp" class="w-64 h-64 object-contain mx-auto" />
        </div>
    @elseif (!$connected)
        <p class="text-slate-500 text-sm">QR code belum tersedia. Klik <strong>Reconnect</strong> untuk meminta QR baru.</p>
    @endif

    @if (!empty($data['tenantId']))
        <p class="mt-4 text-xs text-slate-500">Tenant: {{ $data['tenantId'] }}</p>
    @endif
</div>

<div class="mt-4 text-sm text-slate-500">
    Setiap request ke backend memakai API key client (Bearer token).
</div>
@endsection
