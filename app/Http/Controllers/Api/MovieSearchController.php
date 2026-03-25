<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Services\MovieSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieSearchController extends Controller
{
    public function __construct(
        private readonly MovieSyncService $movieSyncService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->string('q'));

        if ($search !== '') {
            $this->movieSyncService->syncSearchResults($search);
        }

        $query = Movie::query()
            ->with(['showtimes', 'reviews'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('imdb_id', 'like', "%{$search}%")
                    ->orWhere('tagline', 'like', "%{$search}%")
                    ->orWhere('genre', 'like', "%{$search}%");
            });
        }

        if ($genre = $request->string('genre')->toString()) {
            $query->where('genre', $genre);
        }

        match ($request->string('sort')->toString()) {
            'title' => $query->orderBy('title'),
            'release' => $query->orderByDesc('release_year'),
            default => $query->orderByDesc('critic_score'),
        };

        $movies = $query->get();

        return response()->json([
            'count' => $movies->count(),
            'html' => $movies->isNotEmpty()
                ? $movies->map(fn (Movie $movie) => view('partials.movie-card', ['movie' => $movie])->render())->implode('')
                : view('partials.movie-empty')->render(),
            'suggestions' => $movies->take(5)->map(fn (Movie $movie) => [
                'title' => $movie->title,
                'url' => route('movies.show', $movie),
            ])->values(),
        ]);
    }
}
