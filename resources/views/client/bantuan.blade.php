<x-client-layout>
    <x-slot name="header">Bantuan</x-slot>

    <div class="max-w-2xl space-y-6">
        <p class="text-sm text-slate-600 dark:text-slate-400">
            Panduan singkat penggunaan WA Blast. Gunakan menu di sidebar untuk berpindah fitur.
        </p>

        <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-5">
            <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">1. Login</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400">Buka aplikasi di browser, masukkan email dan password, lalu klik Log in. Setelah login Anda masuk ke Dashboard.</p>
        </section>

        <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">2. Menu</h3>
            </div>
            <div class="p-4">
                <table class="min-w-full text-sm">
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <tr><td class="py-2.5 pr-4 font-medium text-slate-700 dark:text-slate-300 w-24 align-top">Dashboard</td><td class="py-2.5 text-slate-600 dark:text-slate-400">Beranda, ringkasan campaign, API key.</td></tr>
                        <tr><td class="py-2.5 pr-4 font-medium text-slate-700 dark:text-slate-300 align-top">Kontak</td><td class="py-2.5 text-slate-600 dark:text-slate-400">Kelola daftar kontak untuk broadcast.</td></tr>
                        <tr><td class="py-2.5 pr-4 font-medium text-slate-700 dark:text-slate-300 align-top">Segment</td><td class="py-2.5 text-slate-600 dark:text-slate-400">Kelompok kontak untuk target campaign.</td></tr>
                        <tr><td class="py-2.5 pr-4 font-medium text-slate-700 dark:text-slate-300 align-top">Template</td><td class="py-2.5 text-slate-600 dark:text-slate-400">Template pesan untuk campaign.</td></tr>
                        <tr><td class="py-2.5 pr-4 font-medium text-slate-700 dark:text-slate-300 align-top">Campaign</td><td class="py-2.5 text-slate-600 dark:text-slate-400">Buat dan lihat campaign broadcast.</td></tr>
                        <tr><td class="py-2.5 pr-4 font-medium text-slate-700 dark:text-slate-300 align-top">WhatsApp</td><td class="py-2.5 text-slate-600 dark:text-slate-400">Koneksi / scan QR WhatsApp.</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-5">
            <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">3. Koneksi WhatsApp</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">Agar bisa mengirim pesan, akun WhatsApp harus terhubung dulu lewat scan QR code.</p>
            <ol class="text-sm text-slate-600 dark:text-slate-400 space-y-1.5 list-decimal list-inside mb-4">
                <li>Klik <strong>WhatsApp</strong> di sidebar.</li>
                <li>Cek status: Terhubung (hijau) atau Belum terhubung (kuning).</li>
                <li>Jika belum terhubung: tunggu QR code muncul, lalu di HP buka WhatsApp → Menu → Perangkat tertaut → Sambungkan perangkat → Scan QR.</li>
                <li>Setelah scan berhasil, halaman akan memuat ulang otomatis.</li>
            </ol>
            <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                <p class="text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Tips</p>
                <p class="text-xs text-slate-600 dark:text-slate-400">QR diperbarui otomatis setiap 15 detik. Jika gagal, klik Muat ulang QR. Reconnect untuk generate ulang koneksi.</p>
            </div>
        </section>

        <p class="text-sm text-slate-600 dark:text-slate-400 pt-2 border-t border-slate-200 dark:border-slate-700">
            Untuk kirim pesan, pastikan WhatsApp terhubung via <a href="{{ route('client.whatsapp.index') }}" class="text-slate-800 dark:text-slate-100 font-medium hover:underline">halaman WhatsApp</a>.
        </p>
    </div>
</x-client-layout>
