@extends('cms.layout')

@section('title', 'Broadcast')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-slate-800">Broadcast</h1>
    <a href="{{ route('cms.broadcasts.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">
        Buat Broadcast
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase">Nama / Template</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase">Client</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase">Jadwal</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase">Status</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-slate-600 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($broadcasts as $broadcast)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3">
                        <span class="font-medium text-slate-800">{{ $broadcast->name ?: $broadcast->messageTemplate->name }}</span>
                        <span class="text-slate-500 text-sm block">{{ $broadcast->messageTemplate->name }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-600">{{ $broadcast->client->name }}</td>
                    <td class="px-4 py-3 text-sm text-slate-600">
                        @if ($broadcast->scheduled_at)
                            {{ $broadcast->scheduled_at->format('d/m/Y H:i') }}
                        @else
                            Langsung
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $badge = match($broadcast->status) {
                                'pending' => 'bg-amber-100 text-amber-800',
                                'processing' => 'bg-blue-100 text-blue-800',
                                'completed' => 'bg-emerald-100 text-emerald-800',
                                'failed' => 'bg-red-100 text-red-800',
                                default => 'bg-slate-100 text-slate-800',
                            };
                        @endphp
                        <span class="px-2 py-1 rounded text-xs font-medium {{ $badge }}">{{ $broadcast->status }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('cms.broadcasts.show', $broadcast) }}" class="text-emerald-600 hover:underline">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada broadcast. Upload CSV recipient dan pilih template.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if ($broadcasts->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">
            {{ $broadcasts->links() }}
        </div>
    @endif
</div>
@endsection
