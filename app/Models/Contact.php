<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contact extends Model
{
    protected $fillable = [
        'client_id',
        'phone',
        'name',
        'email',
        'tags',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function segments(): BelongsToMany
    {
        return $this->belongsToMany(Segment::class, 'contact_segment');
    }
}
