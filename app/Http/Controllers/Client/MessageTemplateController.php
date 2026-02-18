<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\MessageTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MessageTemplateController extends Controller
{
    public function index(): View
    {
        $client = auth()->user()->client;

        $templates = MessageTemplate::query()
            ->where('client_id', $client->id)
            ->latest()
            ->paginate(10);

        return view('client.message-templates.index', compact('templates'));
    }

    public function create(): View
    {
        return view('client.message-templates.create');
    }

    public function store(): RedirectResponse
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $client = auth()->user()->client;

        MessageTemplate::query()->create([
            'client_id' => $client->id,
            'name' => $data['name'],
            'body' => $data['body'],
        ]);

        return redirect()->route('client.message-templates.index')
            ->with('success', 'Template pesan berhasil dibuat.');
    }

    public function edit(MessageTemplate $template): View|RedirectResponse
    {
        if ($template->client_id !== auth()->user()->client->id) {
            abort(403);
        }

        return view('client.message-templates.edit', compact('template'));
    }

    public function update(MessageTemplate $template): RedirectResponse
    {
        if ($template->client_id !== auth()->user()->client->id) {
            abort(403);
        }

        $data = request()->validate([
            'name' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $template->update($data);

        return redirect()->route('client.message-templates.index')
            ->with('success', 'Template pesan berhasil diperbarui.');
    }

    public function destroy(MessageTemplate $template): RedirectResponse
    {
        if ($template->client_id !== auth()->user()->client->id) {
            abort(403);
        }

        $template->delete();

        return redirect()->route('client.message-templates.index')
            ->with('success', 'Template pesan berhasil dihapus.');
    }
}
