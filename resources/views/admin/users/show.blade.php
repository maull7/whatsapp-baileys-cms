<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail User
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</dt>
                            <dd class="mt-1">
                                <span class="px-2 py-1 text-xs rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </dd>
                        </div>

                        @if ($user->isClient())
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Client</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->client->name ?? '-' }}</dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">API Key</dt>
                                <dd class="mt-1">
                                    <code class="block bg-gray-100 dark:bg-gray-900 px-4 py-3 rounded-lg text-sm break-all">{{ $user->api_key }}</code>
                                    <form action="{{ route('admin.users.regenerate-api-key', $user) }}" method="POST" class="mt-2" onsubmit="return confirm('Yakin generate ulang API key? Key lama tidak akan bisa dipakai lagi.');">
                                        @csrf
                                        <button type="submit" class="text-sm text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300">
                                            🔄 Generate Ulang API Key
                                        </button>
                                    </form>
                                </dd>
                            </div>
                        @endif

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->created_at->format('d M Y H:i') }}</dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Update</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->updated_at->format('d M Y H:i') }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6 flex gap-3">
                        <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">Edit</a>
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
