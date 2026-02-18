<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Services\WhatsAppApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class WhatsAppConnectionController extends Controller
{
    public function show(Client $client, WhatsAppApiService $api): View|RedirectResponse
    {
        $result = $api->getConnectionStatus($client);

        if (! $result['success']) {
            return redirect()->route('cms.clients.index')
                ->with('error', $result['error'] ?? 'Gagal mengambil status WhatsApp.');
        }

        $qrImageUrl = $result['qrImageUrl'] ?? null;
        $useQrProxy = ! $result['connected'] && empty($qrImageUrl);

        return view('cms.clients.whatsapp', [
            'client' => $client,
            'connected' => $result['connected'],
            'data' => $result['data'] ?? [],
            'qrImageUrl' => $qrImageUrl,
            'useQrProxy' => $useQrProxy,
            'status' => $result['status'] ?? null,
        ]);
    }

    public function qrImage(Client $client, WhatsAppApiService $api): Response
    {
        $result = $api->getQrImage($client);

        if (! $result['success']) {
            return response('', 404);
        }

        return response($result['body'], 200, [
            'Content-Type' => $result['contentType'] ?? 'image/png',
        ]);
    }

    public function reconnect(Client $client, WhatsAppApiService $api): RedirectResponse
    {
        $result = $api->reconnect($client);

        if (! $result['success']) {
            return redirect()->route('cms.clients.whatsapp', $client)
                ->with('error', $result['error'] ?? 'Gagal reconnect.');
        }

        return redirect()->route('cms.clients.whatsapp', $client)
            ->with('success', 'Reconnect berhasil. Scan QR jika muncul.');
    }

    public function logout(Client $client, WhatsAppApiService $api): RedirectResponse
    {
        $result = $api->logout($client);

        if (! $result['success']) {
            return redirect()->route('cms.clients.whatsapp', $client)
                ->with('error', $result['error'] ?? 'Gagal logout.');
        }

        return redirect()->route('cms.clients.whatsapp', $client)
            ->with('success', 'Logout berhasil.');
    }
}
