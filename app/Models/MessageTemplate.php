<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MessageTemplate extends Model
{
    protected $fillable = [
        'client_id',
        'name',
        'body',
        'variables',
    ];

    protected function casts(): array
    {
        return [
            'variables' => 'array',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function broadcasts(): HasMany
    {
        return $this->hasMany(Broadcast::class);
    }

    public function renderBody(array $replace = []): string
    {
        $body = $this->body ?? '';
        foreach ($replace as $key => $value) {
            if ($value === null) {
                $value = '';
            }
            $body = str_replace('{{'.$key.'}}', (string) $value, $body);
        }

        return trim($body) !== '' ? $body : ' ';
    }
}
