@extends('layouts.app')

@section('title', 'ReelRoute | Home')

@section('content')
    {{-- Main welcome section with the primary actions --}}
    <section class="section-block simple-hero">
        <div class="simple-hero-copy">
            <h1>Plan a film night with live showtimes, nearby cinemas, and weather-aware travel.</h1>
            <p>Pick a title, save it to your shortlist, then head to the movies page to search and plan around your location.</p>
            <div class="hero-actions">
                <a class="button button-primary" href="{{ route('movies.index') }}">Browse movies</a>
                <a class="button button-secondary" href="{{ route('movies.index') }}#trip-tools">Find cinemas near me</a>
            </div>
        </div>
    </section>

    {{-- Saved shortlist on the left and simple site stats on the right --}}
    <section class="dashboard-grid">
        <article class="section-block">
            <div class="section-heading">
                <div>
                    <h2>Tonight's shortlist</h2>
                </div>
            </div>
            <div class="planner-list" data-planner-list>
                <p class="planner-empty">No films saved yet.</p>
            </div>
        </article>

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
    </section>

    {{-- Featured movies shown on the home page --}}
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

    {{-- Upcoming showtimes pulled from the database --}}
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
