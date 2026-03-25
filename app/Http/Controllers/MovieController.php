<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\View\View;

class MovieController extends Controller
{
    public function index(): View
    {
        $movies = Movie::query()
            ->with(['showtimes', 'reviews'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('featured')
            ->orderByDesc('critic_score')
            ->get();

        $genres = Movie::query()
            ->orderBy('genre')
            ->pluck('genre')
            ->unique()
            ->values();

        return view('movies.index', compact('movies', 'genres'));
    }

    public function show(Movie $movie): View
    {
        $movie->load(['showtimes', 'reviews'])
            ->loadCount('reviews')
            ->loadAvg('reviews', 'rating');

        $relatedMovies = Movie::query()
            ->with(['showtimes', 'reviews'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->whereKeyNot($movie->getKey())
            ->where('genre', $movie->genre)
            ->take(3)
            ->get();

        return view('movies.show', compact('movie', 'relatedMovies'));
    }
}
