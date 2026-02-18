<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Broadcast extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'client_id',
        'message_template_id',
        'segment_id',
        'name',
        'scheduled_at',
        'status',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function messageTemplate(): BelongsTo
    {
        return $this->belongsTo(MessageTemplate::class);
    }

    public function segment(): BelongsTo
    {
        return $this->belongsTo(Segment::class);
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(BroadcastRecipient::class);
    }

    public function isScheduled(): bool
    {
        return $this->scheduled_at !== null;
    }
}
