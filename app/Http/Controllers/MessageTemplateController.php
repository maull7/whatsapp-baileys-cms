<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageTemplateRequest;
use App\Http\Requests\UpdateMessageTemplateRequest;
use App\Models\Client;
use App\Models\MessageTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MessageTemplateController extends Controller
{
    public function index(): View
    {
        $templates = MessageTemplate::query()
            ->with('client')
            ->latest()
            ->paginate(10);

        return view('cms.templates.index', compact('templates'));
    }

    public function create(): View
    {
        $clients = Client::query()->orderBy('name')->get();

        return view('cms.templates.create', compact('clients'));
    }

    public function store(StoreMessageTemplateRequest $request): RedirectResponse
    {
        MessageTemplate::query()->create($request->validated());

        return redirect()->route('cms.templates.index')
            ->with('success', 'Template pesan berhasil ditambah.');
    }

    public function edit(MessageTemplate $template): View
    {
        return view('cms.templates.edit', compact('template'));
    }

    public function update(UpdateMessageTemplateRequest $request, MessageTemplate $template): RedirectResponse
    {
        $template->update($request->validated());

        return redirect()->route('cms.templates.index')
            ->with('success', 'Template pesan berhasil diubah.');
    }

    public function destroy(MessageTemplate $template): RedirectResponse
    {
        $template->delete();

        return redirect()->route('cms.templates.index')
            ->with('success', 'Template pesan berhasil dihapus.');
    }
}
