<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Client::query()->latest()->paginate(10);

        return view('cms.clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('cms.clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        Client::query()->create($request->validated());

        return redirect()->route('cms.clients.index')
            ->with('success', 'Client berhasil ditambah.');
    }

    public function edit(Client $client): View
    {
        return view('cms.clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());

        return redirect()->route('cms.clients.index')
            ->with('success', 'Client berhasil diubah.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('cms.clients.index')
            ->with('success', 'Client berhasil dihapus.');
    }
}
