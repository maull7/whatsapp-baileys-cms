<?php

namespace App\Jobs;

use App\Models\Broadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessBroadcastJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Broadcast $broadcast
    ) {}

    public function handle(): void
    {
        $this->broadcast->update([
            'status' => Broadcast::STATUS_PROCESSING,
            'started_at' => now(),
        ]);

        $recipients = $this->broadcast->recipients()->where('status', 'pending')->get();
        $delaySeconds = 0;
        foreach ($recipients as $recipient) {
            SendSingleBroadcastJob::dispatch($recipient)->delay(now()->addSeconds($delaySeconds));
            $delaySeconds += 2;
        }

        if ($recipients->isEmpty()) {
            $this->broadcast->update([
                'status' => Broadcast::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);
        }
    }
}
