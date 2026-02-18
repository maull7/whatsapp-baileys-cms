<?php

namespace App\Console\Commands;

use App\Models\Broadcast;
use App\Services\BroadcastSenderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessScheduledBroadcastsCommand extends Command
{
    protected $signature = 'broadcasts:process-scheduled';

    protected $description = 'Proses broadcast yang sudah jadwal (kirim langsung, tanpa queue)';

    public function handle(BroadcastSenderService $sender): int
    {
        $due = Broadcast::query()
            ->where('status', Broadcast::STATUS_PENDING)
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($due->isEmpty()) {
            $this->info('Tidak ada broadcast terjadwal yang due.');

            return self::SUCCESS;
        }

        Log::info('Broadcast terjadwal: memproses '.$due->count().' broadcast', [
            'broadcast_ids' => $due->pluck('id')->toArray(),
        ]);
        $this->info('Memproses '.$due->count().' broadcast terjadwal...');

        foreach ($due as $broadcast) {
            $this->info("  Broadcast #{$broadcast->id} (jadwal: {$broadcast->scheduled_at})");
            Log::info('Broadcast terjadwal mulai', [
                'broadcast_id' => $broadcast->id,
                'scheduled_at' => $broadcast->scheduled_at->toIso8601String(),
            ]);

            $result = $sender->sendBroadcast($broadcast);

            $this->info("    Selesai: terkirim {$result['sent']}, gagal {$result['failed']}");
            Log::info('Broadcast terjadwal selesai', [
                'broadcast_id' => $broadcast->id,
                'sent' => $result['sent'],
                'failed' => $result['failed'],
            ]);
        }

        return self::SUCCESS;
    }
}
