<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSendMessageRequest;
use App\Services\WhatsAppApiService;
use Illuminate\Http\RedirectResponse;

class SendMessageController extends Controller
{
    public function store(StoreSendMessageRequest $request, WhatsAppApiService $api): RedirectResponse
    {
        $validated = $request->validated();

        $client = auth()->user()->client;
        $result = $api->sendMessage($client, $validated['number'], $validated['message']);

        if ($result['success']) {
            return back()->with('success', 'Pesan terkirim.');
        }

        return back()->with('error', $result['error'] ?? 'Gagal mengirim pesan.');
    }
}
