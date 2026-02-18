<?php

namespace App\Jobs;

use App\Models\BroadcastRecipient;
use App\Services\WhatsAppApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendSingleBroadcastJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public BroadcastRecipient $recipient
    ) {}

    public function handle(WhatsAppApiService $api): void
    {
        try {
            $broadcast = $this->recipient->broadcast;
            $client = $broadcast->client;
            $template = $broadcast->messageTemplate;

            $replace = [
                'nama' => $this->recipient->name ?? $this->recipient->phone ?? '',
                'phone' => $this->recipient->phone ?? '',
            ];

            if (! empty(trim((string) $this->recipient->message))) {
                $message = str_replace(['{{nama}}', '{{phone}}'], [$replace['nama'], $replace['phone']], $this->recipient->message);
            } else {
                $message = $template->renderBody($replace);
            }

            if (trim((string) $message) === '') {
                $message = $template->body ?: ' ';
            }

            $result = $api->sendMessage($client, $this->recipient->phone, (string) $message);

            if ($result['success']) {
                $this->recipient->update([
                    'status' => BroadcastRecipient::STATUS_SENT,
                    'sent_at' => now(),
                    'error' => null,
                ]);
            } else {
                $this->recipient->update([
                    'status' => BroadcastRecipient::STATUS_FAILED,
                    'error' => $result['error'] ?? 'Unknown error',
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('SendSingleBroadcastJob exception', [
                'recipient_id' => $this->recipient->id,
                'error' => $e->getMessage(),
            ]);
            $this->recipient->update([
                'status' => BroadcastRecipient::STATUS_FAILED,
                'error' => $e->getMessage(),
            ]);
        }

        $this->checkBroadcastCompleted($this->recipient->broadcast);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('SendSingleBroadcastJob failed', [
            'recipient_id' => $this->recipient->id,
            'error' => $exception->getMessage(),
        ]);
        $this->recipient->update([
            'status' => BroadcastRecipient::STATUS_FAILED,
            'error' => $exception->getMessage(),
        ]);
        $this->checkBroadcastCompleted($this->recipient->broadcast);
    }

    protected function checkBroadcastCompleted($broadcast): void
    {
        $broadcast->refresh();
        $pending = $broadcast->recipients()->where('status', BroadcastRecipient::STATUS_PENDING)->exists();
        if (! $pending) {
            $broadcast->update([
                'status' => \App\Models\Broadcast::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);
        }
    }
}
