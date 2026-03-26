<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

/**
 * This service talks to the OMDb API.
 */
class OmdbService
{
    /**
     * Check if an OMDb API key has been added.
     */
    public function isConfigured(): bool
    {
        return filled(config('services.omdb.key'));
    }

    /**
     * Load one movie from OMDb by its IMDb ID.
     */
    public function findByImdbId(string $imdbId): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::acceptJson()
                ->timeout(12)
                ->get(config('services.omdb.url'), [
                    'apikey' => config('services.omdb.key'),
                    'i' => $imdbId,
                    'type' => 'movie',
                    'plot' => 'short',
                ]);
        } catch (ConnectionException) {
            return null;
        }

        if ($response->failed() || $response->json('Response') === 'False') {
            return null;
        }

        return $response->json();
    }

    /**
     * Search OMDb for movies that match a text query.
     */
    public function search(string $query, int $page = 1): array
    {
        if (! $this->isConfigured()) {
            return [];
        }

        try {
            $response = Http::acceptJson()
                ->timeout(12)
                ->get(config('services.omdb.url'), [
                    'apikey' => config('services.omdb.key'),
                    's' => $query,
                    'type' => 'movie',
                    'page' => $page,
                ]);
        } catch (ConnectionException) {
            return [];
        }

        if ($response->failed() || $response->json('Response') === 'False') {
            return [];
        }

        return $response->json('Search', []);
    }
}
