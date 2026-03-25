<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Showtime extends Model
{
    protected $fillable = [
        'movie_id',
        'venue_name',
        'city',
        'starts_at',
        'screen_format',
        'available_seats',
        'ticket_price',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ticket_price' => 'decimal:2',
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
