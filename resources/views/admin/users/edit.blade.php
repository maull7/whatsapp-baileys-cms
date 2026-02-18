<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit User
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200">
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mt-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password <span class="text-xs text-gray-500">(kosongkan jika tidak ingin ubah)</span></label>
                            <input type="password" name="password" id="password"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                            <select name="role" id="role" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih role</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="client" {{ old('role', $user->role) === 'client' ? 'selected' : '' }}>Client</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4" id="client-field" style="display: {{ old('role', $user->role) === 'client' ? 'block' : 'none' }};">
                            <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Client</label>
                            <select name="client_id" id="client_id"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', $user->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">Update</button>
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('role').addEventListener('change', function() {
            const clientField = document.getElementById('client-field');
            const clientSelect = document.getElementById('client_id');
            
            if (this.value === 'client') {
                clientField.style.display = 'block';
                clientSelect.required = true;
            } else {
                clientField.style.display = 'none';
                clientSelect.required = false;
            }
        });
    </script>
</x-admin-layout>
