<x-client-layout>
    <x-slot name="header">Koneksi WhatsApp</x-slot>

    <div class="max-w-2xl space-y-4">
        @if (session('success'))
            <div class="px-4 py-3 rounded-lg bg-green-50 dark:bg-green-950/50 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="px-4 py-3 rounded-lg bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 overflow-hidden">
            <div class="p-5 space-y-5">
                <div>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2">Status</p>
                    @if (!$status['success'])
                        <div class="flex gap-3 p-4 rounded-lg bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-800">
                            <span class="h-2.5 w-2.5 rounded-full bg-red-500 shrink-0 mt-0.5"></span>
                            <div class="min-w-0">
                                <p class="font-medium text-red-800 dark:text-red-200 text-sm">Tidak dapat memeriksa status</p>
                                <p class="text-xs text-red-600 dark:text-red-300 mt-0.5">{{ $status['error'] ?? 'Pastikan server WhatsApp API (Node) berjalan dan API key client benar.' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="flex gap-3 p-4 rounded-lg {{ $status['connected'] ? 'bg-green-50 dark:bg-green-950/50 border border-green-200 dark:border-green-800' : 'bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50' }}">
                            <span class="h-2.5 w-2.5 rounded-full shrink-0 mt-0.5 {{ $status['connected'] ? 'bg-green-500' : 'bg-amber-500' }} @if($status['connected']) animate-pulse @endif"></span>
                            <div class="min-w-0">
                                <p class="font-medium text-sm {{ $status['connected'] ? 'text-green-800 dark:text-green-200' : 'text-amber-800 dark:text-amber-200' }}">
                                    @if ($status['connected'])
                                        {{ $status['statusMessage'] ?: 'Terhubung ke WhatsApp' }}
                                    @else
                                        {{ $status['statusMessage'] ?: 'Belum terhubung — scan QR code di bawah' }}
                                    @endif
                                </p>
                                @if ($status['connected'] && $status['phoneNumber'])
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Nomor: <span class="font-mono">{{ $status['phoneNumber'] }}</span></p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                @php $showQR = $status['success'] && ($status['needQR'] || !$status['connected']); @endphp
                @if ($showQR)
                    <div>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2">Scan QR Code</p>
                        <div class="flex flex-col items-center p-6 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                            <div id="qr-container" class="text-center w-full flex flex-col items-center">
                                @if (!empty($status['qrImageUrl']))
                                    <img id="qr-image" src="{{ $status['qrImageUrl'] }}" alt="QR Code WhatsApp" class="max-w-[260px] rounded-md border border-slate-200 dark:border-slate-600" style="display: block;">
                                    <div id="qr-loading" style="display: none;"></div>
                                    <div id="qr-error" style="display: none;"></div>
                                @else
                                    <img id="qr-image" src="" alt="QR Code WhatsApp" class="max-w-[260px] rounded-md border border-slate-200 dark:border-slate-600" style="display: none;">
                                    <div id="qr-loading" class="flex flex-col items-center py-8">
                                        <svg class="animate-spin h-10 w-10 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Memuat QR Code...</p>
                                    </div>
                                    <div id="qr-error" style="display: none;" class="text-center py-6">
                                        <p class="text-red-600 dark:text-red-400 text-sm font-medium">Gagal memuat QR Code.</p>
                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Pastikan server WhatsApp API berjalan, lalu klik Muat ulang QR.</p>
                                    </div>
                                @endif
                            </div>
                            <p class="mt-5 text-sm text-slate-600 dark:text-slate-400 text-center max-w-xs">
                                Buka WhatsApp di HP → Menu (⋮) → Perangkat tertaut → Sambungkan perangkat → Scan QR di atas.
                            </p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">QR diperbarui otomatis setiap 15 detik.</p>
                            @if (empty($status['qrImageUrl']))
                                <button type="button" id="btn-reload-qr" class="mt-4 px-3 py-2 text-sm rounded-lg border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                    Muat ulang QR
                                </button>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="flex flex-wrap gap-2 pt-4 border-t border-slate-200 dark:border-slate-700">
                    @if ($status['success'] && $status['connected'])
                        <form action="{{ route('client.whatsapp.logout') }}" method="POST" onsubmit="return confirm('Yakin ingin logout dari WhatsApp?');">
                            @csrf
                            <button type="submit" class="px-3 py-2 text-sm rounded-md bg-red-600 text-white hover:bg-red-700">Logout WhatsApp</button>
                        </form>
                    @endif
                    <form action="{{ route('client.whatsapp.reconnect') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-2 text-sm rounded-lg bg-slate-800 dark:bg-slate-100 text-white dark:text-slate-900 hover:bg-slate-700 dark:hover:bg-slate-200 transition-colors">Reconnect</button>
                    </form>
                    <button type="button" onclick="location.reload()" class="px-3 py-2 text-sm rounded-lg border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">Refresh</button>
                </div>
            </div>
        </section>
    </div>

    @if ($showQR && empty($status['qrImageUrl']))
    <script>
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
                if (qrImage) { qrImage.src = src; qrImage.style.display = 'block'; }
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
                img.onload = function() { showQr(url); };
                img.onerror = function() {
                    if (retryCount < maxRetries - 1) setTimeout(function() { loadQRCode(retryCount + 1); }, retryDelay);
                    else showError();
                };
                img.src = url;
            }
            function startQRRefresh() {
                if (document.getElementById('qr-container') && qrImage && qrImage.style.display === 'block') loadQRCode(0);
            }
            function checkStatus() {
                fetch('{{ route('client.whatsapp.status') }}', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (data && data.connected === true) {
                            if (statusCheckInterval) clearInterval(statusCheckInterval);
                            document.body.insertAdjacentHTML('beforeend', '<div id="connected-toast" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"><div class="bg-white dark:bg-slate-900 rounded-lg px-5 py-3 shadow-lg text-green-600 dark:text-green-400 text-sm font-medium">Terhubung! Memuat ulang...</div></div>');
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
    </script>
    @endif
</x-client-layout>
