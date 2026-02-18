@extends('cms.layout')

@section('title', 'User')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-slate-800">User Login</h1>
    <a href="{{ route('cms.users.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">
        Tambah User
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase">Nama</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase">Email</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 uppercase">Client</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($users as $user)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 text-sm text-slate-800">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-sm text-slate-600">{{ $user->email }}</td>
                    <td class="px-4 py-3 text-sm text-slate-600">{{ $user->client?->name ?? 'Admin' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center text-slate-500">Belum ada user. Tambah user untuk client login.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if ($users->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
