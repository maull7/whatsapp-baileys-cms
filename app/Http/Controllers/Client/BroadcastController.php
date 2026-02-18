<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBroadcastRequest;
use App\Imports\BroadcastRecipientsImport;
use App\Models\Broadcast;
use App\Models\MessageTemplate;
use App\Models\Segment;
use App\Services\BroadcastSenderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BroadcastController extends Controller
{
    public function index(): View
    {
        $client = auth()->user()->client;

        $broadcasts = Broadcast::query()
            ->where('client_id', $client->id)
            ->with(['messageTemplate', 'segment'])
            ->withCount(['recipients as sent_count' => fn ($q) => $q->where('status', 'sent'), 'recipients as failed_count' => fn ($q) => $q->where('status', 'failed')])
            ->latest()
            ->paginate(10);

        return view('client.broadcasts.index', compact('broadcasts'));
    }

    public function create(): View
    {
        $client = auth()->user()->client;
        $templates = MessageTemplate::query()
            ->where('client_id', $client->id)
            ->orderBy('name')
            ->get();
        $segments = Segment::query()
            ->where('client_id', $client->id)
            ->withCount('contacts')
            ->orderBy('name')
            ->get();

        return view('client.broadcasts.create', compact('templates', 'segments'));
    }

    public function store(StoreBroadcastRequest $request, BroadcastSenderService $sender): RedirectResponse
    {
        $client = auth()->user()->client;

        if (! $request->segment_id && ! $request->hasFile('recipients_file')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['recipients_file' => 'Pilih segment atau upload file penerima.']);
        }

        $rows = [];

        if ($request->segment_id) {
            $segment = Segment::query()
                ->where('client_id', $client->id)
                ->findOrFail($request->segment_id);
            $maxRows = config('broadcast.max_recipients_per_file', 20);
            $contacts = $segment->contacts()->limit($maxRows)->get();
            if ($contacts->isEmpty()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['segment_id' => 'Segment ini tidak memiliki kontak. Tambah kontak ke segment dulu.']);
            }
            foreach ($contacts as $c) {
                $rows[] = ['phone' => $c->phone, 'name' => $c->name, 'message' => null];
            }
        } else {
            $path = $request->file('recipients_file')->getRealPath();
            $import = new BroadcastRecipientsImport;
            $rows = $import->toArray($path);

            if (empty($rows)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['recipients_file' => 'File tidak berisi data valid. Pastikan ada kolom phone/nomor.']);
            }

            $maxRows = config('broadcast.max_recipients_per_file', 20);
            if (count($rows) > $maxRows) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['recipients_file' => 'Maksimal '.$maxRows.' baris per file. File Anda: '.count($rows).' baris.']);
            }

            $phones = array_column($rows, 'phone');
            $counts = array_count_values($phones);
            $duplicates = array_keys(array_filter($counts, fn (int $c): bool => $c > 1));
            if ($duplicates !== []) {
                $list = implode(', ', array_slice($duplicates, 0, 5));
                if (count($duplicates) > 5) {
                    $list .= ' dan '.(count($duplicates) - 5).' nomor lainnya';
                }

                return redirect()->back()
                    ->withInput()
                    ->withErrors(['recipients_file' => 'Satu file tidak boleh berisi nomor yang sama. Duplikat: '.$list]);
            }
        }

        $template = MessageTemplate::query()->findOrFail($request->message_template_id);
        if ($template->client_id != $client->id) {
            return redirect()->back()->withInput()->withErrors(['message_template_id' => 'Template tidak valid.']);
        }

        $broadcast = Broadcast::query()->create([
            'client_id' => $client->id,
            'message_template_id' => $request->message_template_id,
            'segment_id' => $request->segment_id,
            'name' => $request->name,
            'scheduled_at' => $request->scheduled_at,
            'status' => Broadcast::STATUS_PENDING,
        ]);

        foreach ($rows as $row) {
            $broadcast->recipients()->create([
                'phone' => $row['phone'],
                'name' => $row['name'] ?? null,
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

            return redirect()->route('client.broadcasts.show', $broadcast)
                ->with('success', $msg);
        }

        return redirect()->route('client.broadcasts.show', $broadcast)
            ->with('success', 'Campaign dijadwalkan. Akan terkirim otomatis sesuai jam yang ditentukan (pastikan scheduler Laravel berjalan).');
    }

    public function show(Broadcast $broadcast): View
    {
        $client = auth()->user()->client;

        if ($broadcast->client_id !== $client->id) {
            abort(403);
        }

        $broadcast->load(['messageTemplate', 'segment', 'recipients']);

        return view('client.broadcasts.show', compact('broadcast'));
    }

    public function downloadCsvTemplate(): Response
    {
        $csv = "phone,nama\n6285161234567,John Doe";

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_broadcast.csv"',
        ]);
    }

    public function downloadExcelTemplate(): Response
    {
        $csv = "phone,nama\n6285161234567,John Doe";

        return response($csv, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="template_broadcast.xls"',
        ]);
    }
}
