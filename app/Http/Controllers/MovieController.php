<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Services\MovieSyncService;
use Illuminate\View\View;

/**
 * This controller handles the main movie pages.
 */
class MovieController extends Controller
{
    public function __construct(
        private readonly MovieSyncService $movieSyncService,
    ) {}

    /**
     * Show the full movie list and the filter options.
     */
    public function index(): View
    {
        $movies = Movie::query()
            ->cardData()
            ->orderByDesc('featured')
            ->catalogueSort()
            ->get();

        $genres = Movie::genreOptions();

        return view('movies.index', compact('movies', 'genres'));
    }

    /**
     * Show one movie with its details, reviews, and related titles.
     */
    public function show(Movie $movie): View
    {
        $movie = $this->movieSyncService->refreshMovie($movie);

        $movie->load(['showtimes', 'reviews'])
            ->loadCount('reviews')
            ->loadAvg('reviews', 'rating');

        $relatedMovies = Movie::query()
            ->cardData()
            ->whereKeyNot($movie->getKey())
            ->where('genre', $movie->genre)
            ->take(3)
            ->get();

        return view('movies.show', compact('movie', 'relatedMovies'));
    }
}
