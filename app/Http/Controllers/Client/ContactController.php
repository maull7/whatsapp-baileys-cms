<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Imports\BroadcastRecipientsImport;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $client = auth()->user()->client;

        $contacts = Contact::query()
            ->where('client_id', $client->id)
            ->latest()
            ->paginate(20);

        return view('client.contacts.index', compact('contacts'));
    }

    public function create(): View
    {
        return view('client.contacts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $client = auth()->user()->client;

        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'tags' => 'nullable|string|max:255',
        ]);

        $phone = preg_replace('/\D/', '', $validated['phone']);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        } elseif (! str_starts_with($phone, '62')) {
            $phone = '62'.$phone;
        }

        Contact::query()->updateOrCreate(
            [
                'client_id' => $client->id,
                'phone' => $phone,
            ],
            [
                'name' => $validated['name'] ?? null,
                'email' => $validated['email'] ?? null,
                'tags' => $validated['tags'] ?? null,
            ]
        );

        return redirect()->route('client.contacts.index')
            ->with('success', 'Kontak berhasil disimpan.');
    }

    public function edit(Contact $contact): View|RedirectResponse
    {
        if ($contact->client_id !== auth()->user()->client->id) {
            abort(403);
        }

        return view('client.contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact): RedirectResponse
    {
        if ($contact->client_id !== auth()->user()->client->id) {
            abort(403);
        }

        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'tags' => 'nullable|string|max:255',
        ]);

        $phone = preg_replace('/\D/', '', $validated['phone']);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        } elseif (! str_starts_with($phone, '62')) {
            $phone = '62'.$phone;
        }

        $contact->update([
            'phone' => $phone,
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? null,
            'tags' => $validated['tags'] ?? null,
        ]);

        return redirect()->route('client.contacts.index')
            ->with('success', 'Kontak berhasil diubah.');
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        if ($contact->client_id !== auth()->user()->client->id) {
            abort(403);
        }

        $contact->delete();

        return redirect()->route('client.contacts.index')
            ->with('success', 'Kontak berhasil dihapus.');
    }

    public function importForm(): View
    {
        return view('client.contacts.import');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xls,xlsx|max:5120',
        ]);

        $client = auth()->user()->client;
        $path = $request->file('file')->getRealPath();
        $import = new BroadcastRecipientsImport;
        $rows = $import->toArray($path);

        $max = 500;
        if (count($rows) > $max) {
            return redirect()->back()
                ->withErrors(['file' => "Maksimal {$max} baris per file. File Anda: ".count($rows).' baris.']);
        }

        $phones = array_column($rows, 'phone');
        $counts = array_count_values($phones);
        $duplicates = array_keys(array_filter($counts, fn (int $c): bool => $c > 1));
        if ($duplicates !== []) {
            return redirect()->back()
                ->withErrors(['file' => 'Ada nomor duplikat di file. Satu nomor hanya boleh sekali.']);
        }

        $imported = 0;
        foreach ($rows as $row) {
            Contact::query()->updateOrCreate(
                ['client_id' => $client->id, 'phone' => $row['phone']],
                ['name' => $row['name'] ?? null]
            );
            $imported++;
        }

        return redirect()->route('client.contacts.index')
            ->with('success', "{$imported} kontak berhasil diimpor.");
    }
}
