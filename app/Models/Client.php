<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'name',
        'api_key',
        'api_base_url',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function messageTemplates(): HasMany
    {
        return $this->hasMany(MessageTemplate::class);
    }

    public function broadcasts(): HasMany
    {
        return $this->hasMany(Broadcast::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function segments(): HasMany
    {
        return $this->hasMany(Segment::class);
    }
}
