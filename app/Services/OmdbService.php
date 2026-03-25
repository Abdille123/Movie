<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OmdbService
{
    public function isConfigured(): bool
    {
        return filled(config('services.omdb.key'));
    }

    public function findByImdbId(string $imdbId): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $response = Http::acceptJson()
            ->timeout(12)
            ->get(config('services.omdb.url'), [
                'apikey' => config('services.omdb.key'),
                'i' => $imdbId,
                'type' => 'movie',
                'plot' => 'short',
            ]);

        if ($response->failed() || $response->json('Response') === 'False') {
            return null;
        }

        return $response->json();
    }

    public function search(string $query, int $page = 1): array
    {
        if (! $this->isConfigured()) {
            return [];
        }

        $response = Http::acceptJson()
            ->timeout(12)
            ->get(config('services.omdb.url'), [
                'apikey' => config('services.omdb.key'),
                's' => $query,
                'type' => 'movie',
                'page' => $page,
            ]);

        if ($response->failed() || $response->json('Response') === 'False') {
            return [];
        }

        return $response->json('Search', []);
    }
}
