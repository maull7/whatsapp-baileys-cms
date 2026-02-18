@extends('cms.layout')

@section('title', 'Template Pesan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-slate-800">Template Pesan</h1>
    <a href="{{ route('cms.templates.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">
        Tambah Template
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase">Nama</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase">Client</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase">Isi (potongan)</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-slate-600 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($templates as $template)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 text-sm font-medium text-slate-800">{{ $template->name }}</td>
                    <td class="px-4 py-3 text-sm text-slate-600">{{ $template->client->name }}</td>
                    <td class="px-4 py-3 text-sm text-slate-600 max-w-xs truncate">{{ Str::limit($template->body, 60) }}</td>
                    <td class="px-4 py-3 text-right text-sm">
                        <a href="{{ route('cms.templates.edit', $template) }}" class="text-emerald-600 hover:underline mr-3">Edit</a>
                        <form action="{{ route('cms.templates.destroy', $template) }}" method="POST" class="inline" onsubmit="return confirm('Hapus template ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">Belum ada template. Gunakan variabel  di isi pesan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if ($templates->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">
            {{ $templates->links() }}
        </div>
    @endif
</div>
@endsection
