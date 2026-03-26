<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * This model stores one user review for a movie.
 */
class Review extends Model
{
    protected $fillable = [
        'movie_id',
        'author_name',
        'rating',
        'comment',
        'favourite_scene',
        'would_rewatch',
    ];

    protected $casts = [
        'would_rewatch' => 'boolean',
    ];

    /**
     * Get the movie that this review belongs to.
     */
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
