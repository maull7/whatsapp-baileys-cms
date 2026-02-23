<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            WhatsApp Connection
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div
                    class="mb-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div
                    class="mb-4 px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Status Koneksi</h3>

                        @if (!$status['success'])
                            <div class="flex items-center">
                                <span class="flex h-3 w-3 mr-2">
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                </span>
                                <span class="text-red-600 dark:text-red-400 font-medium">Tidak dapat memeriksa
                                    status</span>
                            </div>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                {{ $status['error'] ?? 'Pastikan server WhatsApp API (Node) berjalan dan API key client benar.' }}
                            </p>
                        @else
                            <div class="flex items-center">
                                @if ($status['connected'])
                                    <div class="flex items-center">
                                        <span class="flex h-3 w-3 mr-2">
                                            <span
                                                class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-green-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                        </span>
                                        <span class="text-green-600 dark:text-green-400 font-medium">Terhubung ke
                                            WhatsApp</span>
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <span class="flex h-3 w-3 mr-2">
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                                        </span>
                                        <span class="text-amber-600 dark:text-amber-400 font-medium">Belum terhubung —
                                            scan QR code di bawah</span>
                                    </div>
                                @endif
                            </div>
                            @if ($status['connected'] && $status['phoneNumber'])
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Nomor: <span
                                        class="font-mono font-semibold">{{ $status['phoneNumber'] }}</span></p>
                            @endif
                        @endif
                    </div>

                    @if ($status['success'] && !$status['connected'])
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Scan QR Code</h3>
                            <div class="flex justify-center p-6 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                <div id="qr-container" class="text-center">
                                    <img id="qr-image" src="" alt="QR Code WhatsApp"
                                        class="mx-auto max-w-xs rounded-lg shadow-lg" style="display: none;">
                                    <div id="qr-loading" class="flex flex-col items-center">
                                        <svg class="animate-spin h-12 w-12 text-emerald-600"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <p class="mt-4 text-gray-600 dark:text-gray-400">Memuat QR Code...</p>
                                    </div>
                                    <div id="qr-error" style="display: none;" class="text-red-600 dark:text-red-400">
                                        <p>Gagal memuat QR Code.</p>
                                        <p class="mt-2 text-sm">Pastikan server WhatsApp API berjalan. Lalu klik
                                            <strong>Refresh</strong> atau <strong>Reconnect</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 text-center">
                                Buka WhatsApp di HP → Ketuk menu (titik tiga) → Pilih <strong>Perangkat Tertaut</strong>
                                → Scan QR code di atas
                            </p>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-500 text-center">QR diperbarui otomatis setiap 15 detik. Setelah scan berhasil, koneksi terdeteksi dalam beberapa detik.</p>
                            <div class="mt-3 flex justify-center">
                                <button type="button" id="btn-reload-qr" class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    Muat ulang QR
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-wrap gap-3">
                        @if ($status['success'] && $status['connected'])
                            <form action="{{ route('client.whatsapp.logout') }}" method="POST"
                                onsubmit="return confirm('Yakin ingin logout dari WhatsApp?');">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">Logout
                                    WhatsApp</button>
                            </form>
                        @endif

                        <form action="{{ route('client.whatsapp.reconnect') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">Reconnect</button>
                        </form>

                        <button type="button" onclick="location.reload()"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">Refresh</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        @if ($status['success'] && !$status['connected'])
            (function() {
                var qrImage = document.getElementById('qr-image');
                var qrLoading = document.getElementById('qr-loading');
                var qrError = document.getElementById('qr-error');
                var btnReload = document.getElementById('btn-reload-qr');
                var maxRetries = 3;
                var retryDelay = 2000;
                var refreshInterval = 15000;
                var pollInterval = 3000;
                var statusCheckInterval;

                function showLoading() {
                    if (qrLoading) qrLoading.style.display = 'flex';
                    if (qrImage) qrImage.style.display = 'none';
                    if (qrError) qrError.style.display = 'none';
                }

                function showQr(src) {
                    if (qrImage) {
                        qrImage.src = src;
                        qrImage.style.display = 'block';
                    }
                    if (qrLoading) qrLoading.style.display = 'none';
                    if (qrError) qrError.style.display = 'none';
                }

                function showError() {
                    if (qrLoading) qrLoading.style.display = 'none';
                    if (qrImage) qrImage.style.display = 'none';
                    if (qrError) qrError.style.display = 'block';
                }

                function loadQRCode(retryCount) {
                    retryCount = retryCount || 0;
                    showLoading();
                    var url = '{{ route('client.whatsapp.qr-image') }}?t=' + Date.now();
                    var img = new Image();
                    img.onload = function() {
                        showQr(url);
                    };
                    img.onerror = function() {
                        if (retryCount < maxRetries - 1) {
                            setTimeout(function() { loadQRCode(retryCount + 1); }, retryDelay);
                        } else {
                            showError();
                        }
                    };
                    img.src = url;
                }

                function startQRRefresh() {
                    if (document.getElementById('qr-container') && document.getElementById('qr-image').style.display === 'block') {
                        loadQRCode(0);
                    }
                }

                function checkStatus() {
                    fetch('{{ route('client.whatsapp.status') }}', {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (data && data.connected) {
                            if (statusCheckInterval) clearInterval(statusCheckInterval);
                            document.body.insertAdjacentHTML('beforeend', '<div id="connected-toast" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"><div class="bg-white dark:bg-gray-800 rounded-lg px-6 py-4 shadow-xl text-green-600 dark:text-green-400 font-medium">Terhubung! Memuat ulang...</div></div>');
                            setTimeout(function() { location.reload(); }, 800);
                        }
                    })
                    .catch(function() {});
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', function() {
                        loadQRCode(0);
                        statusCheckInterval = setInterval(checkStatus, pollInterval);
                        setInterval(startQRRefresh, refreshInterval);
                        if (btnReload) btnReload.addEventListener('click', function() { loadQRCode(0); });
                    });
                } else {
                    loadQRCode(0);
                    statusCheckInterval = setInterval(checkStatus, pollInterval);
                    setInterval(startQRRefresh, refreshInterval);
                    if (btnReload) btnReload.addEventListener('click', function() { loadQRCode(0); });
                }
            })();
        @endif
    </script>
</x-client-layout>
