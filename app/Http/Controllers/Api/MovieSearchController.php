<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Services\MovieSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * This API controller powers the AJAX movie search.
 */
class MovieSearchController extends Controller
{
    public function __construct(
        private readonly MovieSyncService $movieSyncService,
    ) {}

    /**
     * Return filtered movie cards and quick suggestion links.
     */
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->string('q'));
        $genre = $request->string('genre')->toString();
        $sort = $request->string('sort')->toString();

        if ($search !== '') {
            $this->movieSyncService->syncSearchResults($search);
        }

        $movies = Movie::query()
            ->cardData()
            ->searchTerm($search)
            ->genreFilter($genre)
            ->catalogueSort($sort)
            ->get();

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
