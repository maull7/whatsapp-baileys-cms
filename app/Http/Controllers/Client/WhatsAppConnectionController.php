<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class WhatsAppConnectionController extends Controller
{
    public function show(WhatsAppApiService $api): View|RedirectResponse
    {
        $client = auth()->user()->client;
        $result = $api->getConnectionStatus($client);

        if (! $result['success']) {
            return redirect()->route('client.dashboard')
                ->with('error', $result['error'] ?? 'Gagal mengambil status WhatsApp.');
        }

        $qrImageUrl = $result['qrImageUrl'] ?? null;
        $useQrProxy = ! $result['connected'] && empty($qrImageUrl);

        return view('client.whatsapp', [
            'client' => $client,
            'connected' => $result['connected'],
            'data' => $result['data'] ?? [],
            'qrImageUrl' => $qrImageUrl,
            'useQrProxy' => $useQrProxy,
            'status' => $result['status'] ?? null,
        ]);
    }

    public function qrImage(WhatsAppApiService $api): Response
    {
        $client = auth()->user()->client;
        $result = $api->getQrImage($client);

        if (! $result['success']) {
            return response('', 404);
        }

        return response($result['body'], 200, [
            'Content-Type' => $result['contentType'] ?? 'image/png',
        ]);
    }

    public function reconnect(WhatsAppApiService $api): RedirectResponse
    {
        $client = auth()->user()->client;
        $result = $api->reconnect($client);

        if (! $result['success']) {
            return redirect()->route('client.whatsapp')
                ->with('error', $result['error'] ?? 'Gagal reconnect.');
        }

        return redirect()->route('client.whatsapp')
            ->with('success', 'Reconnect berhasil. Scan QR jika muncul.');
    }

    public function logout(WhatsAppApiService $api): RedirectResponse
    {
        $client = auth()->user()->client;
        $result = $api->logout($client);

        if (! $result['success']) {
            return redirect()->route('client.whatsapp')
                ->with('error', $result['error'] ?? 'Gagal logout WhatsApp.');
        }

        return redirect()->route('client.whatsapp')
            ->with('success', 'Logout WhatsApp berhasil.');
    }
}
