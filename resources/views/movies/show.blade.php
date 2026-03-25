@extends('layouts.app')

@section('title', "ReelRoute | {$movie->title}")

@section('content')
    <section class="detail-hero tone-{{ $movie->tone }}">
        <div class="detail-poster">
            @if ($movie->poster_url)
                <img class="detail-poster-image" src="{{ $movie->poster_url }}" alt="{{ $movie->title }} poster" referrerpolicy="no-referrer">
            @endif
            <div class="poster-content">
                <span>{{ $movie->genre }}</span>
                <strong>{{ $movie->title }}</strong>
                <small>{{ $movie->tagline }}</small>
            </div>
        </div>

        <div class="detail-copy">
            <a class="text-link" href="{{ route('movies.index') }}">Back to catalogue</a>
            <h1>{{ $movie->title }}</h1>
            <p class="detail-tagline">{{ $movie->tagline }}</p>
            <p>{{ $movie->synopsis }}</p>
            @if ($movie->imdb_id)
                <p class="movie-source">IMDb ID: {{ $movie->imdb_id }}{{ $movie->director ? ' · '.$movie->director : '' }}</p>
            @endif

            <div class="detail-metrics">
                <span>{{ $movie->release_year }}</span>
                <span>{{ $movie->runtime_minutes }} mins</span>
                <span>{{ $movie->age_rating }}</span>
                <span>{{ $movie->critic_score }} critic</span>
                <span>{{ $movie->audience_score }} audience</span>
            </div>

            <div class="hero-actions">
                <button
                    class="button button-primary"
                    type="button"
                    data-plan-button
                    data-movie-id="{{ $movie->id }}"
                    data-movie-title="{{ $movie->title }}"
                    data-movie-url="{{ route('movies.show', $movie) }}"
                >
                    Add to shortlist
                </button>
                <a class="button button-secondary" href="#reviews">Jump to reviews</a>
            </div>
        </div>
    </section>

    <section class="detail-layout">
        <div class="section-block">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Showtimes</span>
                    <h2>Upcoming screenings</h2>
                </div>
            </div>

            <div class="timeline-list">
                @foreach ($movie->showtimes as $showtime)
                    <article class="timeline-item">
                        <div>
                            <strong>{{ $showtime->venue_name }}</strong>
                            <p>{{ $showtime->city }} · {{ $showtime->screen_format }} · £{{ number_format((float) $showtime->ticket_price, 2) }}</p>
                        </div>
                        <div class="timeline-meta">
                            <span>{{ $showtime->starts_at->format('D j M') }}</span>
                            <strong>{{ $showtime->starts_at->format('H:i') }}</strong>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <aside class="tools-stack">
            <article class="tool-card">
                <span class="eyebrow">Review summary</span>
                <h2><span data-review-average>{{ number_format((float) ($movie->reviews_avg_rating ?? 0), 1) }}</span>/5 average</h2>
                <p><strong data-review-count>{{ $movie->reviews_count }}</strong> audience reviews stored in the database.</p>
                @if ($movie->last_synced_at)
                    <p class="status-copy">Movie details last synced {{ $movie->last_synced_at->diffForHumans() }}.</p>
                @endif
            </article>
        </aside>
    </section>

    <section class="detail-layout" id="reviews">
        <div class="section-block">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">AJAX reviews</span>
                    <h2>Post feedback without reloading the page.</h2>
                </div>
            </div>

            <form class="review-form" data-review-form action="{{ route('movies.reviews.store', $movie) }}" method="post">
                @csrf
                <div class="filter-row">
                    <div class="field-group">
                        <label for="author_name">Name</label>
                        <input id="author_name" name="author_name" type="text" required>
                    </div>
                    <div class="field-group">
                        <label for="rating">Rating</label>
                        <select id="rating" name="rating" required>
                            <option value="5">5</option>
                            <option value="4">4</option>
                            <option value="3">3</option>
                            <option value="2">2</option>
                            <option value="1">1</option>
                        </select>
                    </div>
                </div>

                <div class="field-group">
                    <label for="comment">Comment</label>
                    <textarea id="comment" name="comment" rows="4" required></textarea>
                </div>

                <div class="filter-row">
                    <div class="field-group">
                        <label for="favourite_scene">Favourite scene</label>
                        <input id="favourite_scene" name="favourite_scene" type="text">
                    </div>
                    <label class="checkbox-field">
                        <input type="checkbox" name="would_rewatch" value="1" checked>
                        <span>I would watch this again</span>
                    </label>
                </div>

                <div class="form-feedback" data-review-errors></div>
                <button class="button button-primary" type="submit">Send review</button>
            </form>
        </div>

        <aside class="section-block">
            <div class="review-stack" data-review-list>
                @foreach ($movie->reviews as $review)
                    @include('partials.review-card', ['review' => $review])
                @endforeach
            </div>
        </aside>
    </section>

    <section class="section-block">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Related viewing</span>
                <h2>More titles from the same lane.</h2>
            </div>
        </div>

        <div class="movie-grid">
            @foreach ($relatedMovies as $relatedMovie)
                @include('partials.movie-card', ['movie' => $relatedMovie])
            @endforeach
        </div>
    </section>
@endsection
