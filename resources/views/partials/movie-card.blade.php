<article class="movie-card tone-{{ $movie->tone }}">
    <div class="movie-poster">
        @if ($movie->poster_url)
            <img class="movie-poster-image" src="{{ $movie->poster_url }}" alt="{{ $movie->title }} poster" referrerpolicy="no-referrer">
        @endif
        <div class="poster-content">
            <span>{{ $movie->genre }}</span>
            <strong>{{ $movie->title }}</strong>
            <small>{{ $movie->release_year }}</small>
        </div>
    </div>

    <div class="movie-copy">
        <div class="movie-scores">
            <span>{{ $movie->critic_score }} critic</span>
            <span>{{ $movie->audience_score }} audience</span>
            <span>{{ $movie->runtime_minutes }} mins</span>
        </div>

        <h3>{{ $movie->title }}</h3>
        <p>{{ $movie->short_synopsis }}</p>
        @if ($movie->director)
            <p class="movie-source">IMDb-linked data · {{ $movie->director }}</p>
        @endif

        <div class="movie-meta">
            <div>
                <strong>{{ $movie->showtimes->first()?->starts_at?->format('D H:i') ?? 'TBA' }}</strong>
                <span>{{ $movie->showtimes->first()?->venue_name ?? 'Schedule coming soon' }}</span>
            </div>
            <div>
                <strong>{{ number_format((float) ($movie->reviews_avg_rating ?? 0), 1) }}/5</strong>
                <span>{{ $movie->reviews_count }} reviews</span>
            </div>
        </div>

        <div class="card-actions">
            <a class="button button-secondary" href="{{ route('movies.show', $movie) }}">View details</a>
            <button
                class="button button-ghost"
                type="button"
                data-plan-button
                data-movie-id="{{ $movie->id }}"
                data-movie-title="{{ $movie->title }}"
                data-movie-url="{{ route('movies.show', $movie) }}"
            >
                Add to shortlist
            </button>
        </div>
    </div>
</article>
