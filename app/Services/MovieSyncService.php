<?php

namespace App\Services;

use App\Models\Movie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * This service keeps local movies in sync with OMDb and IMDb-linked data.
 */
class MovieSyncService
{
    public function __construct(
        private readonly OmdbService $omdb,
    ) {}

    /**
     * Refresh the featured movies we want on the site.
     */
    public function syncFeaturedMovies(bool $force = false): void
    {
        if (! $this->omdb->isConfigured()) {
            return;
        }

        if ($force) {
            Cache::forget('omdb:featured-sync');
        }

        Cache::remember('omdb:featured-sync', now()->addHours(6), function (): int {
            foreach (config('services.omdb.featured_ids', []) as $imdbId) {
                $this->syncByImdbId($imdbId, [
                    'featured' => true,
                ]);
            }

            return now()->unix();
        });
    }

    /**
     * Import matching OMDb movies when the user searches.
     */
    public function syncSearchResults(string $query): void
    {
        if (! $this->omdb->isConfigured() || strlen($query) < 2) {
            return;
        }

        foreach (array_slice($this->omdb->search($query), 0, 6) as $result) {
            $imdbId = data_get($result, 'imdbID');

            if ($imdbId) {
                $this->syncByImdbId($imdbId);
            }
        }
    }

    /**
     * Refresh one movie if its live data is getting old.
     */
    public function refreshMovie(Movie $movie): Movie
    {
        if (! $this->omdb->isConfigured() || blank($movie->imdb_id)) {
            return $movie;
        }

        if ($movie->last_synced_at?->gt(now()->subHours(12))) {
            return $movie;
        }

        return $this->syncByImdbId($movie->imdb_id, [
            'featured' => $movie->featured,
            'tone' => $movie->tone,
        ]) ?? $movie;
    }

    /**
     * Find one OMDb movie and save it into our database.
     */
    public function syncByImdbId(string $imdbId, array $overrides = []): ?Movie
    {
        $payload = $this->omdb->findByImdbId($imdbId);

        if (! $payload) {
            return null;
        }

        $movie = Movie::query()->firstOrNew([
            'imdb_id' => $imdbId,
        ]);

        $existingValues = [
            'featured' => $movie->exists ? $movie->featured : false,
            'tone' => $movie->exists ? $movie->tone : $this->toneFromGenre($payload['Genre'] ?? ''),
        ];

        $movie->fill(array_merge(
            $this->mapPayloadToAttributes($payload),
            $existingValues,
            $overrides,
        ));

        $movie->save();

        return $movie->fresh();
    }

    /**
     * Turn raw OMDb data into the movie fields our app uses.
     */
    private function mapPayloadToAttributes(array $payload): array
    {
        $imdbId = (string) ($payload['imdbID'] ?? Str::lower(Str::random(8)));
        $genre = Str::of((string) ($payload['Genre'] ?? 'Movie'))->before(',')->trim()->value();
        $director = trim((string) ($payload['Director'] ?? ''));
        $title = trim((string) ($payload['Title'] ?? 'Untitled Movie'));
        $imdbRating = (float) ($payload['imdbRating'] ?? 0);
        $metascore = is_numeric($payload['Metascore'] ?? null) ? (int) $payload['Metascore'] : null;

        return [
            'title' => $title,
            'slug' => Str::slug($title.' '.$imdbId),
            'tagline' => $director && $director !== 'N/A'
                ? 'Directed by '.$director
                : $genre.' film from IMDb',
            'synopsis' => $this->cleanField((string) ($payload['Plot'] ?? 'Plot unavailable.')) ?? 'Plot unavailable.',
            'genre' => $genre,
            'director' => $this->cleanField($director),
            'release_year' => $this->parseYear((string) ($payload['Year'] ?? '2000')),
            'runtime_minutes' => $this->parseRuntime((string) ($payload['Runtime'] ?? '0 min')),
            'age_rating' => $this->cleanField((string) ($payload['Rated'] ?? 'NR')) ?: 'NR',
            'critic_score' => max(0, min(100, $metascore ?? (int) round($imdbRating * 10))),
            'audience_score' => max(0, min(100, (int) round($imdbRating * 10))),
            'poster_url' => $this->normalisePoster((string) ($payload['Poster'] ?? '')),
            'last_synced_at' => now(),
        ];
    }

    /**
     * Pick a colour theme based on the movie genre.
     */
    private function toneFromGenre(string $genres): string
    {
        $genre = Str::of($genres)->lower();

        return match (true) {
            $genre->contains('sci-fi'), $genre->contains('science') => 'cobalt',
            $genre->contains('thriller'), $genre->contains('crime') => 'storm',
            $genre->contains('animation'), $genre->contains('family') => 'mint',
            $genre->contains('romance'), $genre->contains('music') => 'sunset',
            $genre->contains('mystery'), $genre->contains('drama') => 'slate',
            default => 'ember',
        };
    }

    /**
     * Pull the runtime number out of text like "136 min".
     */
    private function parseRuntime(string $runtime): int
    {
        preg_match('/(\d+)/', $runtime, $matches);

        return max(1, (int) ($matches[1] ?? 0));
    }

    /**
     * Pull the year out of text and fall back to the current year if needed.
     */
    private function parseYear(string $year): int
    {
        preg_match('/(\d{4})/', $year, $matches);

        return (int) ($matches[1] ?? now()->year);
    }

    /**
     * Ignore empty poster values and keep only real URLs.
     */
    private function normalisePoster(string $poster): ?string
    {
        $poster = trim($poster);

        if ($poster === '' || $poster === 'N/A') {
            return null;
        }

        return $poster;
    }

    /**
     * Remove blank values and the "N/A" placeholder.
     */
    private function cleanField(string $value): ?string
    {
        $value = trim($value);

        return $value !== '' && $value !== 'N/A' ? $value : null;
    }
}
