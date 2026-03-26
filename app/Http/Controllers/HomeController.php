<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Review;
use App\Models\Showtime;
use App\Services\MovieSyncService;
use Illuminate\View\View;

/**
 * This controller builds the home page.
 */
class HomeController extends Controller
{
    public function __construct(
        private readonly MovieSyncService $movieSyncService,
    ) {}

    /**
     * Show featured movies, upcoming showtimes, and quick stats.
     */
    public function __invoke(): View
    {
        $this->movieSyncService->syncFeaturedMovies();

        $featuredMovies = Movie::query()
            ->with(['showtimes', 'reviews'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('featured', true)
            ->orderByDesc('critic_score')
            ->take(3)
            ->get();

        $upcomingShowtimes = Showtime::query()
            ->with('movie')
            ->orderBy('starts_at')
            ->take(5)
            ->get();

        $stats = [
            'movies' => Movie::count(),
            'reviews' => Review::count(),
            'showtimes' => Showtime::count(),
            'genres' => Movie::distinct('genre')->count('genre'),
        ];

        return view('home', compact('featuredMovies', 'upcomingShowtimes', 'stats'));
    }
}
