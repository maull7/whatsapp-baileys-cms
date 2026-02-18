<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Template Pesan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div
                    class="mb-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div
                class="mb-4 px-4 py-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 text-sm">
                Template pesan dipakai saat membuat broadcast.
            </div>
            <div class="mb-4">
                <a href="{{ route('client.message-templates.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600">
                    Tambah Template
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($templates->count() > 0)
                        <div class="space-y-4">
                            @foreach ($templates as $template)
                                <div
                                    class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $template->name }}
                                    </h3>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">
                                        {{ $template->body }}</p>
                                    <div>
                                        <a href="{{ route('client.message-templates.edit', $template) }}"
                                            class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            Edit
                                        </a>
                                        <form action="{{ route('client.message-templates.destroy', $template) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $templates->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Belum ada template. <a href="{{ route('client.message-templates.create') }}" class="text-emerald-600 hover:underline">Tambah template</a> untuk dipakai di campaign.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-client-layout>
