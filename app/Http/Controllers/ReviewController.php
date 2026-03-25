<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Movie $movie): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:80'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'max:600'],
            'favourite_scene' => ['nullable', 'string', 'max:120'],
            'would_rewatch' => ['nullable', 'boolean'],
        ]);

        $review = $movie->reviews()->create([
            ...$validated,
            'would_rewatch' => (bool) ($validated['would_rewatch'] ?? false),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Review added.',
                'html' => view('partials.review-card', ['review' => $review])->render(),
                'review_count' => $movie->reviews()->count(),
                'average_rating' => round((float) $movie->reviews()->avg('rating'), 1),
            ], 201);
        }

        return redirect()
            ->route('movies.show', $movie)
            ->with('status', 'Review added.');
    }
}
