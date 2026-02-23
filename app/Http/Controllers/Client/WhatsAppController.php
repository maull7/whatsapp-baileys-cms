<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WhatsAppController extends Controller
{
    public function __construct(
        protected WhatsAppApiService $api
    ) {}

    public function index(): View
    {
        $client = auth()->user()->client;
        $result = $this->api->getConnectionStatus($client);

        $status = [
            'success' => $result['success'],
            'connected' => $result['connected'] ?? false,
            'phoneNumber' => $result['data']['phoneNumber'] ?? $result['data']['number'] ?? null,
            'error' => $result['error'] ?? null,
        ];

        return view('client.whatsapp', compact('client', 'status'));
    }

    public function status(): \Illuminate\Http\JsonResponse
    {
        $client = auth()->user()->client;
        $result = $this->api->getConnectionStatus($client);

        return response()->json([
            'success' => $result['success'],
            'connected' => $result['connected'] ?? false,
            'phoneNumber' => $result['data']['phoneNumber'] ?? $result['data']['number'] ?? null,
        ]);
    }

    public function qrImage(): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
    {
        $client = auth()->user()->client;
        $result = $this->api->getQrImage($client);

        if (! $result['success']) {
            return response()->json(['message' => $result['error'] ?? 'Gagal mengambil QR'], 503)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
                ->header('Retry-After', '2');
        }

        return response($result['body'])
            ->header('Content-Type', $result['contentType'] ?? 'image/png')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function logout(): RedirectResponse
    {
        $client = auth()->user()->client;
        $result = $this->api->logout($client);

        return redirect()->route('client.whatsapp.index')
            ->with($result['success'] ? 'success' : 'error', $result['success'] ? 'Berhasil logout dari WhatsApp.' : $result['error']);
    }

    public function reconnect(): RedirectResponse
    {
        $client = auth()->user()->client;
        $result = $this->api->reconnect($client);

        return redirect()->route('client.whatsapp.index')
            ->with($result['success'] ? 'success' : 'error', $result['success'] ? 'Reconnect berhasil.' : $result['error']);
    }
}
