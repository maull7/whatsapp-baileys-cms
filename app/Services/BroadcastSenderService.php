<?php

namespace App\Services;

use App\Models\Broadcast;
use App\Models\BroadcastRecipient;
use Illuminate\Support\Facades\Log;

class BroadcastSenderService
{
    public function __construct(
        protected WhatsAppApiService $api
    ) {}

    /**
     * Kirim broadcast ke semua recipient (tanpa queue). Return [sent, failed, firstError].
     *
     * @return array{sent: int, failed: int, firstError: ?string}
     */
    public function sendBroadcast(Broadcast $broadcast): array
    {
        $broadcast->update(['status' => Broadcast::STATUS_PROCESSING, 'started_at' => now()]);
        $broadcast->load(['client', 'messageTemplate', 'recipients']);

        $client = $broadcast->client;
        $template = $broadcast->messageTemplate;
        $sent = 0;
        $failed = 0;
        $firstError = null;

        Log::info('Broadcast mulai dikirim', [
            'broadcast_id' => $broadcast->id,
            'client' => $client->name,
            'recipient_count' => $broadcast->recipients->count(),
        ]);

        $delaySeconds = config('broadcast.delay_between_recipients_seconds', 2);
        $isFirst = true;

        foreach ($broadcast->recipients as $recipient) {
            if (! $isFirst && $delaySeconds > 0) {
                sleep($delaySeconds);
            }
            $isFirst = false;

            try {
                $replace = [
                    'nama' => $recipient->name ?? $recipient->phone ?? '',
                    'phone' => $recipient->phone ?? '',
                ];
                if (! empty(trim((string) $recipient->message))) {
                    $message = str_replace(['{{nama}}', '{{phone}}'], [$replace['nama'], $replace['phone']], $recipient->message);
                } else {
                    $message = $template->renderBody($replace);
                }
                if (trim((string) $message) === '') {
                    $message = $template->body ?: ' ';
                }

                Log::info('Broadcast kirim ke nomor', ['broadcast_id' => $broadcast->id, 'phone' => $recipient->phone]);

                $result = $this->api->sendMessage($client, $recipient->phone, (string) $message);

                if ($result['success']) {
                    $recipient->update(['status' => BroadcastRecipient::STATUS_SENT, 'sent_at' => now(), 'error' => null]);
                    $sent++;
                    Log::info('Broadcast terkirim', ['broadcast_id' => $broadcast->id, 'phone' => $recipient->phone]);
                } else {
                    $err = $result['error'] ?? 'Unknown error';
                    $recipient->update(['status' => BroadcastRecipient::STATUS_FAILED, 'error' => $err]);
                    $failed++;
                    if ($firstError === null) {
                        $firstError = $err;
                    }
                    Log::warning('Broadcast gagal ke nomor', [
                        'broadcast_id' => $broadcast->id,
                        'phone' => $recipient->phone,
                        'error' => $err,
                    ]);
                }
            } catch (\Throwable $e) {
                $err = $e->getMessage();
                $recipient->update(['status' => BroadcastRecipient::STATUS_FAILED, 'error' => $err]);
                $failed++;
                if ($firstError === null) {
                    $firstError = $err;
                }
                Log::error('Broadcast exception', [
                    'broadcast_id' => $broadcast->id,
                    'recipient_id' => $recipient->id,
                    'phone' => $recipient->phone,
                    'error' => $err,
                ]);
            }
        }

        $broadcast->update(['status' => Broadcast::STATUS_COMPLETED, 'completed_at' => now()]);

        Log::info('Broadcast selesai', [
            'broadcast_id' => $broadcast->id,
            'sent' => $sent,
            'failed' => $failed,
        ]);

        return ['sent' => $sent, 'failed' => $failed, 'firstError' => $firstError];
    }
}
