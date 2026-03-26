<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * This model stores the main movie details.
 */
class Movie extends Model
{
    protected $fillable = [
        'imdb_id',
        'title',
        'slug',
        'tagline',
        'synopsis',
        'genre',
        'director',
        'release_year',
        'runtime_minutes',
        'age_rating',
        'critic_score',
        'audience_score',
        'tone',
        'poster_url',
        'last_synced_at',
        'featured',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Get the reviews that belong to this movie.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    /**
     * Get the showtimes that belong to this movie.
     */
    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class)->orderBy('starts_at');
    }

    /**
     * Use the slug instead of the numeric ID in the URL.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Make a shorter version of the synopsis for the movie cards.
     */
    protected function shortSynopsis(): Attribute
    {
        return Attribute::make(
            get: fn () => str($this->synopsis)->limit(135)->value(),
        );
    }
}
