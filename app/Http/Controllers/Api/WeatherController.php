<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function __construct(
        private readonly WeatherService $weatherService,
    ) {}

    public function show(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
        ]);

        return response()->json(
            $this->weatherService->currentForCinemaTrip(
                latitude: (float) $validated['lat'],
                longitude: (float) $validated['lng'],
            )
        );
    }
}
