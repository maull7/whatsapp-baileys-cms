<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppApiService
{
    protected function baseRequest(Client $client): \Illuminate\Http\Client\PendingRequest
    {
        $baseUrl = rtrim($client->api_base_url ?? config('whatsapp.api.base_url'), '/');
        $timeout = config('whatsapp.api.timeout');

        return Http::timeout($timeout)
            ->withHeaders([
                'Authorization' => 'Bearer '.$client->api_key,
                'X-API-Key' => $client->api_key,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->baseUrl($baseUrl);
    }

    public function getConnectionStatus(Client $client): array
    {
        $req = $this->baseRequest($client);
        $timeout = max(10, (int) config('whatsapp.api.timeout', 30));
        $response = $req->timeout($timeout)->get('/api/status');

        if (! $response->successful()) {
            Log::warning('WhatsApp API status failed', [
                'client_id' => $client->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'connected' => false,
                'error' => $response->json('message') ?? $response->body() ?? 'Gagal mengambil status',
                'data' => null,
            ];
        }

        $data = $response->json('data', []);
        $connected = (bool) ($data['connected'] ?? false);
        $phoneNumber = $data['phoneNumber'] ?? $data['number'] ?? null;

        // Anggap benar-benar terhubung hanya jika ada nomor (hindari state "tersimpan tapi belum konek")
        if ($connected && empty($phoneNumber)) {
            $connected = false;
        }

        return [
            'success' => true,
            'connected' => $connected,
            'data' => $data,
            'qrImageUrl' => $data['qrImageUrl'] ?? $data['qrUrl'] ?? null,
            'status' => $data['status'] ?? null,
        ];
    }

    /**
     * Ambil gambar QR dari endpoint /api/qr/image.
     * Mendukung: raw image (PNG/JPEG) atau JSON dengan base64.
     * Retry 2x dengan jeda 1 detik jika gagal.
     *
     * @return array{success: bool, body?: string, contentType?: string, error?: string}
     */
    public function getQrImage(Client $client): array
    {
        $baseUrl = rtrim($client->api_base_url ?? config('whatsapp.api.base_url'), '/');
        $timeout = (int) config('whatsapp.api.timeout', 30);
        $timeout = max(15, min($timeout, 45));
        $url = $baseUrl.'/api/qr/image';
        $headers = [
            'Authorization' => 'Bearer '.$client->api_key,
            'X-API-Key' => $client->api_key,
            'Accept' => 'image/png, image/jpeg, image/*, application/json, */*',
        ];

        $lastError = null;
        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $response = Http::timeout($timeout)
                ->withHeaders($headers)
                ->get($url);

            if (! $response->successful()) {
                $lastError = $response->json('message') ?? $response->body() ?? 'Gagal mengambil QR';
                if ($attempt < 3) {
                    sleep(1);
                }

                continue;
            }

            $contentType = $response->header('Content-Type') ?: 'image/png';

            // Backend mengembalikan JSON dengan base64
            if (str_contains($contentType, 'application/json')) {
                $json = $response->json();
                $b64 = $json['qr'] ?? $json['image'] ?? $json['data'] ?? null;
                if (is_string($b64)) {
                    if (preg_match('/^data:image\/\w+;base64,(.+)$/', $b64, $m)) {
                        $b64 = $m[1];
                    }
                    $body = base64_decode($b64, true);
                    if ($body !== false && strlen($body) > 100) {
                        return [
                            'success' => true,
                            'body' => $body,
                            'contentType' => 'image/png',
                        ];
                    }
                }
                $lastError = 'Format QR dari backend tidak valid';
                if ($attempt < 3) {
                    sleep(1);
                }

                continue;
            }

            $body = $response->body();
            if (strlen($body) < 100) {
                $lastError = 'Gambar QR kosong atau terlalu kecil';
                if ($attempt < 3) {
                    sleep(1);
                }

                continue;
            }

            return [
                'success' => true,
                'body' => $body,
                'contentType' => $contentType,
            ];
        }

        return [
            'success' => false,
            'error' => $lastError ?? 'Gagal mengambil QR setelah beberapa percobaan',
        ];
    }

    public function reconnect(Client $client): array
    {
        $response = $this->baseRequest($client)->post('/api/reconnect');

        if (! $response->successful()) {
            return [
                'success' => false,
                'error' => $response->json('message') ?? $response->body() ?? 'Gagal reconnect',
            ];
        }

        return ['success' => true, 'data' => $response->json()];
    }

    public function logout(Client $client): array
    {
        $response = $this->baseRequest($client)->post('/api/logout');

        if (! $response->successful()) {
            return [
                'success' => false,
                'error' => $response->json('message') ?? $response->body() ?? 'Gagal logout',
            ];
        }

        return ['success' => true, 'data' => $response->json()];
    }

    public function sendMessage(Client $client, string $phone, string $message): array
    {
        $response = $this->baseRequest($client)->post('/api/send-message', [
            'number' => $this->normalizePhone($phone),
            'message' => $message,
        ]);

        if ($response->successful()) {
            return ['success' => true, 'data' => $response->json()];
        }

        Log::warning('WhatsApp API send failed', [
            'client_id' => $client->id,
            'phone' => $phone,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return [
            'success' => false,
            'error' => $response->json('message') ?? $response->body() ?? 'Unknown error',
        ];
    }

    protected function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        } elseif (! str_starts_with($phone, '62')) {
            $phone = '62'.$phone;
        }

        return $phone;
    }
}
