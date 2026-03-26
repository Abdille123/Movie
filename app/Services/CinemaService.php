<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * This service finds cinemas near the user's location.
 */
class CinemaService
{
    /**
     * Ask OpenStreetMap data for nearby cinemas.
     */
    public function nearby(float $latitude, float $longitude, int $radiusMeters = 8000): array
    {
        $query = <<<QUERY
        [out:json][timeout:25];
        (
          node["amenity"="cinema"](around:{$radiusMeters},{$latitude},{$longitude});
          way["amenity"="cinema"](around:{$radiusMeters},{$latitude},{$longitude});
          relation["amenity"="cinema"](around:{$radiusMeters},{$latitude},{$longitude});
        );
        out center 20;
        QUERY;

        $response = Http::acceptJson()
            ->withHeaders([
                'User-Agent' => 'MovieNightPlanner/1.0',
            ])
            ->timeout(15)
            ->asForm()
            ->post('https://overpass-api.de/api/interpreter', [
                'data' => $query,
            ]);

        if ($response->failed()) {
            return [];
        }

        return collect($response->json('elements', []))
            ->map(fn (array $cinema) => $this->transformCinema($cinema, $latitude, $longitude))
            ->filter()
            ->sortBy('distance_km')
            ->take(8)
            ->values()
            ->all();
    }

    /**
     * Clean one raw cinema result and add distance data.
     */
    private function transformCinema(array $cinema, float $originLat, float $originLng): ?array
    {
        $latitude = data_get($cinema, 'lat', data_get($cinema, 'center.lat'));
        $longitude = data_get($cinema, 'lon', data_get($cinema, 'center.lon'));

        if ($latitude === null || $longitude === null) {
            return null;
        }

        $tags = data_get($cinema, 'tags', []);
        $street = trim(collect([
            $tags['addr:housenumber'] ?? null,
            $tags['addr:street'] ?? null,
        ])->filter()->implode(' '));

        $locality = collect([
            $tags['addr:city'] ?? null,
            $tags['addr:postcode'] ?? null,
        ])->filter()->implode(', ');

        return [
            'name' => $tags['name'] ?? 'Nearby cinema',
            'address' => trim(collect([$street, $locality])->filter()->implode(' | ')),
            'latitude' => (float) $latitude,
            'longitude' => (float) $longitude,
            'distance_km' => round($this->distanceKm($originLat, $originLng, (float) $latitude, (float) $longitude), 1),
        ];
    }

    /**
     * Work out the distance between two map points in kilometres.
     */
    private function distanceKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLng = deg2rad($lng2 - $lng1);

        $a = sin($deltaLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($deltaLng / 2) ** 2;

        return 2 * $earthRadius * atan2(sqrt($a), sqrt(1 - $a));
    }
}
