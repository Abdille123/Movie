@extends('layouts.app')

@section('title', 'ReelRoute | Home')

@section('content')
    <section class="hero-panel">
        <div class="hero-copy">
            <span class="eyebrow">Assessment-ready movie website</span>
            <h1>Plan a film night with live showtimes, nearby cinemas, and weather-aware travel.</h1>
            <p>
                ReelRoute is a simple Laravel movie platform built around MVC, seeded cinema data, AJAX browsing,
                responsive layouts, and browser-powered location features that make sense on mobile.
            </p>
            <div class="hero-actions">
                <a class="button button-primary" href="{{ route('movies.index') }}">Browse movies</a>
                <a class="button button-secondary" href="{{ route('movies.index') }}#trip-tools">Find cinemas near me</a>
            </div>
        </div>

        <div class="hero-stack">
            <article class="highlight-card">
                <h2>Why it scores well</h2>
                <ul class="check-list">
                    <li>MVC architecture with migrations, models, controllers, views, and seed data.</li>
                    <li>Two live integrations: nearby cinemas and cinema-trip weather.</li>
                    <li>Rich interactions: AJAX search, review posting, live shortlist updates.</li>
                    <li>Responsive layout built for laptop and phone widths.</li>
                </ul>
            </article>

            <article class="planner-card">
                <h2>Tonight's shortlist</h2>
                <p>Use the add buttons across the site to store films in the browser for quick comparison.</p>
                <div class="planner-list" data-planner-list>
                    <p class="planner-empty">No films saved yet.</p>
                </div>
            </article>
        </div>
    </section>

    <section class="stats-grid">
        <article class="stat-card">
            <span>Movies</span>
            <strong>{{ $stats['movies'] }}</strong>
        </article>
        <article class="stat-card">
            <span>Showtimes</span>
            <strong>{{ $stats['showtimes'] }}</strong>
        </article>
        <article class="stat-card">
            <span>Reviews</span>
            <strong>{{ $stats['reviews'] }}</strong>
        </article>
        <article class="stat-card">
            <span>Genres</span>
            <strong>{{ $stats['genres'] }}</strong>
        </article>
    </section>

    <section class="section-block">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Featured picks</span>
                <h2>Three strong options for a quick movie night.</h2>
            </div>
            <a class="text-link" href="{{ route('movies.index') }}">See the full catalogue</a>
        </div>

        <div class="movie-grid">
            @foreach ($featuredMovies as $movie)
                @include('partials.movie-card', ['movie' => $movie])
            @endforeach
        </div>
    </section>

    <section class="section-block schedule-section">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Upcoming screenings</span>
                <h2>Database-backed sessions seeded through Laravel.</h2>
            </div>
        </div>

        <div class="timeline-list">
            @foreach ($upcomingShowtimes as $showtime)
                <article class="timeline-item">
                    <div>
                        <strong>{{ $showtime->movie->title }}</strong>
                        <p>{{ $showtime->venue_name }} · {{ $showtime->city }}</p>
                    </div>
                    <div class="timeline-meta">
                        <span>{{ $showtime->starts_at->format('D j M') }}</span>
                        <strong>{{ $showtime->starts_at->format('H:i') }}</strong>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
