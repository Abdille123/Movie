<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

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
     * Load the related data needed for movie cards and catalogue lists.
     */
    public function scopeCardData(Builder $query): Builder
    {
        return $query
            ->with(['showtimes', 'reviews'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');
    }

    /**
     * Filter movies by a search term when one is provided.
     */
    public function scopeSearchTerm(Builder $query, ?string $search): Builder
    {
        $search = trim((string) $search);

        if ($search === '') {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($search): void {
            $builder
                ->where('title', 'like', "%{$search}%")
                ->orWhere('imdb_id', 'like', "%{$search}%")
                ->orWhere('tagline', 'like', "%{$search}%")
                ->orWhere('genre', 'like', "%{$search}%");
        });
    }

    /**
     * Filter movies by genre when the user picks one.
     */
    public function scopeGenreFilter(Builder $query, ?string $genre): Builder
    {
        $genre = trim((string) $genre);

        return $genre === ''
            ? $query
            : $query->where('genre', $genre);
    }

    /**
     * Apply the catalogue sort order used by the app.
     */
    public function scopeCatalogueSort(Builder $query, ?string $sort = 'score'): Builder
    {
        return match ($sort) {
            'title' => $query->orderBy('title'),
            'release' => $query->orderByDesc('release_year'),
            default => $query->orderByDesc('critic_score'),
        };
    }

    /**
     * Get the distinct genre list used by the filter dropdown.
     */
    public static function genreOptions(): Collection
    {
        return static::query()
            ->orderBy('genre')
            ->pluck('genre')
            ->unique()
            ->values();
    }

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
