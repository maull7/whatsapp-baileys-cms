@extends('cms.layout')

@section('title', 'Detail Broadcast')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-800">Detail Broadcast</h1>
        <p class="text-slate-600 mt-1">{{ $broadcast->name ?: $broadcast->messageTemplate->name }} · {{ $broadcast->client->name }}</p>
    </div>
    <a href="{{ route('cms.broadcasts.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50">Kembali</a>
</div>

<div class="grid gap-6 md:grid-cols-2">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="font-medium text-slate-800 mb-2">Info</h2>
        <dl class="space-y-2 text-sm">
            <div><span class="text-slate-500">Status</span>
                @php
                    $badge = match($broadcast->status) {
                        'pending' => 'bg-amber-100 text-amber-800',
                        'processing' => 'bg-blue-100 text-blue-800',
                        'completed' => 'bg-emerald-100 text-emerald-800',
                        'failed' => 'bg-red-100 text-red-800',
                        default => 'bg-slate-100 text-slate-800',
                    };
                @endphp
                <span class="ml-2 px-2 py-0.5 rounded text-xs font-medium {{ $badge }}">{{ $broadcast->status }}</span>
            </div>
            <div><span class="text-slate-500">Jadwal</span> {{ $broadcast->scheduled_at ? $broadcast->scheduled_at->format('d/m/Y H:i') : 'Langsung' }}</div>
            <div><span class="text-slate-500">Mulai</span> {{ $broadcast->started_at?->format('d/m/Y H:i') ?? '-' }}</div>
            <div><span class="text-slate-500">Selesai</span> {{ $broadcast->completed_at?->format('d/m/Y H:i') ?? '-' }}</div>
        </dl>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="font-medium text-slate-800 mb-2">Template</h2>
        <p class="text-sm text-slate-600 whitespace-pre-wrap">{{ $broadcast->messageTemplate->body }}</p>
    </div>
</div>

<div class="mt-6 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <h2 class="px-4 py-3 border-b border-slate-200 font-medium text-slate-800">Penerima ({{ $broadcast->recipients->count() }})</h2>
    <div class="overflow-x-auto max-h-96 overflow-y-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50 sticky top-0">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-600">No</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-600">Phone</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-600">Nama</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-600">Pesan</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-600">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-600">Error</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach ($broadcast->recipients as $i => $r)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-2 text-sm">{{ $i + 1 }}</td>
                        <td class="px-4 py-2 text-sm font-mono">{{ $r->phone }}</td>
                        <td class="px-4 py-2 text-sm">{{ $r->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-slate-600 max-w-xs truncate" title="{{ $r->message }}">{{ $r->message ? Str::limit($r->message, 40) : '–' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-0.5 rounded text-xs {{ $r->status === 'sent' ? 'bg-emerald-100 text-emerald-800' : ($r->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-800') }}">{{ $r->status }}</span>
                        </td>
                        <td class="px-4 py-2 text-sm text-slate-600 max-w-xs truncate" title="{{ $r->error }}">{{ $r->error ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
