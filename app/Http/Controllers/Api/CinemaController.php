<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CinemaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * This API controller returns nearby cinema results.
 */
class CinemaController extends Controller
{
    public function __construct(
        private readonly CinemaService $cinemaService,
    ) {}

    /**
     * Validate the location and send back cinema data.
     */
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
