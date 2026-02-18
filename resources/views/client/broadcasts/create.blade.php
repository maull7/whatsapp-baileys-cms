<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Buat Broadcast Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 px-4 py-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 text-sm">
                <strong>Info:</strong> Maksimal <strong>20 baris</strong> per file/segment. Tidak boleh ada nomor yang sama dalam 1 file. Jeda pengiriman: <strong>2 detik</strong> antar penerima.
            </div>
            <div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm">
                <strong>Campaign Terjadwal:</strong> Jika Anda isi jadwal, campaign akan terkirim otomatis sesuai jam tanpa perlu reload halaman. Pastikan scheduler Laravel berjalan: <code class="bg-emerald-100 dark:bg-emerald-900/50 px-1 rounded">php artisan schedule:work</code> (development) atau setup cron (production).
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($errors->any())
                        <div
                            class="mb-4 px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200">
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mt-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('client.broadcasts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="message_template_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Template
                                Pesan</label>
                            <select name="message_template_id" id="message_template_id" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih template</option>
                                @foreach ($templates as $template)
                                    <option value="{{ $template->id }}"
                                        {{ old('message_template_id') == $template->id ? 'selected' : '' }}>
                                        {{ $template->name }}</option>
                                @endforeach
                            </select>
                            @error('message_template_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Broadcast
                                <span class="text-xs text-gray-500">(opsional)</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                placeholder="Contoh: Promo Februari 2026"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="scheduled_at"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jadwal Kirim
                                <span class="text-xs text-gray-500">(kosongkan = kirim sekarang)</span></label>
                            <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                                value="{{ old('scheduled_at') }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                min="{{ now()->addMinute()->format('Y-m-d\TH:i') }}">
                            @error('scheduled_at')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Penerima</label>
                            <div class="space-y-2 mb-3">
                                <label class="flex items-center">
                                    <input type="radio" name="target_type" value="segment" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" {{ old('target_type', 'file') === 'segment' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm">Kirim ke Segment</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="target_type" value="file" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" {{ old('target_type', 'file') === 'file' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm">Upload File CSV/Excel</span>
                                </label>
                            </div>

                            <div id="segment-field" class="mb-3" style="display: none;">
                                <label for="segment_id" class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Pilih Segment</label>
                                <select name="segment_id" id="segment_id" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">-- Pilih segment --</option>
                                    @foreach ($segments as $seg)
                                        <option value="{{ $seg->id }}" {{ old('segment_id') == $seg->id ? 'selected' : '' }}>{{ $seg->name }} ({{ $seg->contacts_count }} kontak)</option>
                                    @endforeach
                                </select>
                                @error('segment_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Maks 20 kontak per campaign (diambil dari segment).</p>
                            </div>

                            <div id="file-field">
                                <div class="text-sm mb-2">
                                    <a href="{{ route('client.broadcasts.template') }}" class="text-emerald-600 hover:underline dark:text-emerald-400">Download Template CSV</a>
                                    <span class="text-gray-400 mx-1">|</span>
                                    <a href="{{ route('client.broadcasts.template-excel') }}" class="text-emerald-600 hover:underline dark:text-emerald-400">Download Template Excel</a>
                                </div>
                                <input type="file" name="recipients_file" id="recipients_file" accept=".csv,.txt,.xls,.xlsx"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('recipients_file')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kolom: phone, nama. Maks 20 baris. Tidak boleh nomor duplikat.</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit"
                                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">Kirim
                                Broadcast</button>
                            <a href="{{ route('client.broadcasts.index') }}"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('input[name="target_type"]').forEach(function(r) {
            r.addEventListener('change', function() {
                var isSegment = document.querySelector('input[name="target_type"]:checked').value === 'segment';
                document.getElementById('segment-field').style.display = isSegment ? 'block' : 'none';
                document.getElementById('file-field').style.display = isSegment ? 'none' : 'block';
                document.getElementById('segment_id').required = isSegment;
                document.getElementById('recipients_file').required = !isSegment;
            });
        });
        document.querySelector('input[name="target_type"]:checked').dispatchEvent(new Event('change'));
    </script>
</x-client-layout>
