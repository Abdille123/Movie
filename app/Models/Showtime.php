<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * This model stores one cinema showing for a movie.
 */
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

    /**
     * Get the movie that this showtime belongs to.
     */
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
