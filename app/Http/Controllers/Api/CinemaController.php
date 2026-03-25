<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CinemaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CinemaController extends Controller
{
    public function __construct(
        private readonly CinemaService $cinemaService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
        ]);

        return response()->json([
            'cinemas' => $this->cinemaService->nearby(
                latitude: (float) $validated['lat'],
                longitude: (float) $validated['lng'],
            ),
        ]);
    }
}
