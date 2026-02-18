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
                'Authorization' => 'Bearer ' . $client->api_key,
                'X-API-Key' => $client->api_key,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->baseUrl($baseUrl);
    }

    public function getConnectionStatus(Client $client): array
    {
        $response = $this->baseRequest($client)->get('/api/status');

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

        $qrImageUrl = $data['qrImageUrl'] ?? $data['qrUrl'] ?? null;

        return [
            'success' => true,
            'connected' => $connected,
            'data' => $data,
            'qrImageUrl' => $qrImageUrl,
            'status' => $data['status'] ?? null,
        ];
    }

    /**
     * Ambil gambar QR dari endpoint /api/qr/image (jika status tidak mengembalikan qrImageUrl).
     *
     * @return array{success: bool, body?: string, contentType?: string, error?: string}
     */
    public function getQrImage(Client $client): array
    {
        $baseUrl = rtrim($client->api_base_url ?? config('whatsapp.api.base_url'), '/');
        $timeout = config('whatsapp.api.timeout');

        $response = Http::timeout($timeout)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $client->api_key,
                'X-API-Key' => $client->api_key,
                'Accept' => 'image/png, image/jpeg, image/*, */*',
            ])
            ->get($baseUrl . '/api/qr/image?api_key=' . $client->api_key);

        if (! $response->successful()) {
            return [
                'success' => false,
                'error' => $response->json('message') ?? $response->body() ?? 'Gagal mengambil QR',
            ];
        }

        $contentType = $response->header('Content-Type') ?: 'image/png';

        return [
            'success' => true,
            'body' => $response->body(),
            'contentType' => $contentType,
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
            $phone = '62' . substr($phone, 1);
        } elseif (! str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
