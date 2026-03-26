<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * This service gets weather data for cinema trips.
 */
class WeatherService
{
    /**
     * Load simple weather details for the chosen location.
     */
    public function currentForCinemaTrip(float $latitude, float $longitude): array
    {
        $response = Http::acceptJson()
            ->timeout(12)
            ->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'current' => 'temperature_2m,weather_code,wind_speed_10m',
                'hourly' => 'precipitation_probability',
                'forecast_days' => 1,
                'timezone' => 'auto',
            ]);

        if ($response->failed()) {
            return [
                'summary' => 'Weather is unavailable right now.',
                'advice' => 'You can still use the cinema finder and local showtimes.',
            ];
        }

        $current = $response->json('current', []);
        $precipitation = collect($response->json('hourly.precipitation_probability', []))
            ->take(4)
            ->filter(fn ($value) => $value !== null)
            ->avg();

        $temperature = round((float) ($current['temperature_2m'] ?? 0));
        $wind = round((float) ($current['wind_speed_10m'] ?? 0));
        $summary = $this->weatherSummary((int) ($current['weather_code'] ?? 0));
        $chanceOfRain = (int) round((float) ($precipitation ?? 0));

        return [
            'temperature' => $temperature,
            'wind_speed' => $wind,
            'summary' => $summary,
            'rain_chance' => $chanceOfRain,
            'advice' => $chanceOfRain > 40
                ? 'Take a coat before heading to the cinema.'
                : 'Conditions look comfortable for a cinema trip.',
        ];
    }

    /**
     * Turn weather codes into short plain-English labels.
     */
    private function weatherSummary(int $code): string
    {
        return match (true) {
            in_array($code, [0], true) => 'Clear skies',
            in_array($code, [1, 2, 3], true) => 'Partly cloudy',
            in_array($code, [45, 48], true) => 'Foggy',
            in_array($code, [51, 53, 55, 61, 63, 65, 80, 81, 82], true) => 'Rain expected',
            in_array($code, [71, 73, 75, 85, 86], true) => 'Snow conditions',
            in_array($code, [95, 96, 99], true) => 'Storm risk',
            default => 'Mixed conditions',
        };
    }
}
