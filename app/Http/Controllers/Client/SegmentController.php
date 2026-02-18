<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Segment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SegmentController extends Controller
{
    public function index(): View
    {
        $client = auth()->user()->client;

        $segments = Segment::query()
            ->where('client_id', $client->id)
            ->withCount('contacts')
            ->latest()
            ->paginate(15);

        return view('client.segments.index', compact('segments'));
    }

    public function create(): View
    {
        return view('client.segments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $client = auth()->user()->client;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        Segment::query()->create([
            'client_id' => $client->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('client.segments.index')
            ->with('success', 'Segment berhasil dibuat.');
    }

    public function show(Segment $segment): View|RedirectResponse
    {
        if ($segment->client_id !== auth()->user()->client->id) {
            abort(403);
        }

        $segment->load('contacts');

        return view('client.segments.show', compact('segment'));
    }

    public function edit(Segment $segment): View|RedirectResponse
    {
        if ($segment->client_id !== auth()->user()->client->id) {
            abort(403);
        }

        return view('client.segments.edit', compact('segment'));
    }

    public function update(Request $request, Segment $segment): RedirectResponse
    {
        if ($segment->client_id !== auth()->user()->client->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $segment->update($validated);

        return redirect()->route('client.segments.index')
            ->with('success', 'Segment berhasil diubah.');
    }

    public function destroy(Segment $segment): RedirectResponse
    {
        if ($segment->client_id !== auth()->user()->client->id) {
            abort(403);
        }

        $segment->contacts()->detach();
        $segment->delete();

        return redirect()->route('client.segments.index')
            ->with('success', 'Segment berhasil dihapus.');
    }

    public function addContacts(Segment $segment): View|RedirectResponse
    {
        if ($segment->client_id !== auth()->user()->client->id) {
            abort(403);
        }

        $client = auth()->user()->client;
        $contacts = Contact::query()
            ->where('client_id', $client->id)
            ->whereDoesntHave('segments', fn ($q) => $q->where('segments.id', $segment->id))
            ->orderBy('name')
            ->orderBy('phone')
            ->paginate(20);

        return view('client.segments.add-contacts', compact('segment', 'contacts'));
    }

    public function attachContact(Request $request, Segment $segment): RedirectResponse
    {
        if ($segment->client_id !== auth()->user()->client->id) {
            abort(403);
        }

        $validated = $request->validate(['contact_id' => 'required|exists:contacts,id']);

        $contact = Contact::query()->findOrFail($validated['contact_id']);
        if ($contact->client_id !== auth()->user()->client->id) {
            abort(403);
        }

        $segment->contacts()->syncWithoutDetaching([$contact->id]);

        return redirect()->back()->with('success', 'Kontak ditambahkan ke segment.');
    }

    public function detachContact(Segment $segment, Contact $contact): RedirectResponse
    {
        if ($segment->client_id !== auth()->user()->client->id) {
            abort(403);
        }
        if ($contact->client_id !== auth()->user()->client->id) {
            abort(403);
        }

        $segment->contacts()->detach($contact->id);

        return redirect()->back()->with('success', 'Kontak dikeluarkan dari segment.');
    }
}
