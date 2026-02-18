<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBroadcastRequest;
use App\Imports\BroadcastRecipientsImport;
use App\Models\Broadcast;
use App\Models\Client;
use App\Models\MessageTemplate;
use App\Services\BroadcastSenderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BroadcastController extends Controller
{
    public function index(BroadcastSenderService $sender): View
    {
        $due = Broadcast::query()
            ->where('status', Broadcast::STATUS_PENDING)
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->get();

        foreach ($due as $broadcast) {
            $sender->sendBroadcast($broadcast);
        }

        $broadcasts = Broadcast::query()
            ->with(['client', 'messageTemplate'])
            ->latest()
            ->paginate(10);

        return view('cms.broadcasts.index', compact('broadcasts'));
    }

    public function create(): View
    {
        $clients = Client::query()->orderBy('name')->get();
        $templates = MessageTemplate::query()->with('client')->orderBy('name')->get();

        return view('cms.broadcasts.create', compact('clients', 'templates'));
    }

    public function store(StoreBroadcastRequest $request, BroadcastSenderService $sender): RedirectResponse
    {
        $path = $request->file('recipients_file')->getRealPath();
        $import = new BroadcastRecipientsImport;
        $rows = $import->toArray($path);

        if (empty($rows)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['recipients_file' => 'File CSV tidak berisi data valid. Pastikan ada kolom phone/nomor.']);
        }

        $maxRows = config('broadcast.max_recipients_per_file', 20);
        if (count($rows) > $maxRows) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['recipients_file' => 'Maksimal ' . $maxRows . ' baris per file. File Anda: ' . count($rows) . ' baris.']);
        }

        $phones = array_column($rows, 'phone');
        $counts = array_count_values($phones);
        $duplicates = array_keys(array_filter($counts, fn(int $c): bool => $c > 1));
        if ($duplicates !== []) {
            $list = implode(', ', array_slice($duplicates, 0, 5));
            if (count($duplicates) > 5) {
                $list .= ' dan ' . (count($duplicates) - 5) . ' nomor lainnya';
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['recipients_file' => 'Satu file tidak boleh berisi nomor yang sama. Duplikat: ' . $list]);
        }

        $template = MessageTemplate::query()->findOrFail($request->message_template_id);
        if ($template->client_id != $request->client_id) {
            return redirect()->back()->withInput()->withErrors(['message_template_id' => 'Template tidak sesuai dengan client.']);
        }

        $broadcast = Broadcast::query()->create([
            'client_id' => Auth::user()->client_id,
            'message_template_id' => $request->message_template_id,
            'name' => $request->name,
            'scheduled_at' => $request->scheduled_at,
            'status' => Broadcast::STATUS_PENDING,
        ]);

        foreach ($rows as $row) {
            $broadcast->recipients()->create([
                'phone' => $row['phone'],
                'name' => $row['name'],
                'message' => $row['message'] ?? null,
                'status' => 'pending',
            ]);
        }

        if (! $request->scheduled_at) {
            $result = $sender->sendBroadcast($broadcast);
            $msg = 'Terkirim: ' . $result['sent'] . ', Gagal: ' . $result['failed'] . '.';
            if ($result['firstError'] !== null) {
                $msg .= ' Contoh error: ' . Str::limit($result['firstError'], 100);
            }

            return redirect()->route('cms.broadcasts.show', $broadcast)
                ->with('success', $msg);
        }

        return redirect()->route('cms.broadcasts.show', $broadcast)
            ->with('success', 'Broadcast dijadwalkan. Akan diproses saat waktunya tiba (buka halaman Daftar Broadcast atau detail broadcast).');
    }

    public function show(Broadcast $broadcast, BroadcastSenderService $sender): View
    {
        if (
            $broadcast->status === Broadcast::STATUS_PENDING
            && $broadcast->scheduled_at !== null
            && $broadcast->scheduled_at->isPast()
        ) {
            $sender->sendBroadcast($broadcast);
            $broadcast->refresh();
        }

        $broadcast->load(['client', 'messageTemplate', 'recipients']);

        return view('cms.broadcasts.show', compact('broadcast'));
    }
}
