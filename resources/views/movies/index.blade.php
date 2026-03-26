@extends('layouts.app')

@section('title', 'ReelRoute | Movies')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <h1>Browse movies and plan your next cinema trip.</h1>
            </div>
        </div>

        {{-- AJAX search box with filters and suggestion dropdown --}}
        <form class="filters-card" data-catalogue-form action="{{ route('api.movies.search') }}" method="get">
            <div class="field-group search-stack">
                <label for="search">Search movies</label>
                <input id="search" name="q" type="search" placeholder="Search by title, genre or IMDb ID" autocomplete="off">
                <div class="suggestions-list" data-suggestions></div>
            </div>

            <div class="filter-row">
                <div class="field-group">
                    <label for="genre">Genre</label>
                    <select id="genre" name="genre">
                        <option value="">All genres</option>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre }}">{{ $genre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field-group">
                    <label for="sort">Sort</label>
                    <select id="sort" name="sort">
                        <option value="score">Top rated</option>
                        <option value="release">Newest</option>
                        <option value="title">Title</option>
                    </select>
                </div>
            </div>

            <p class="results-note"><span data-search-feedback>AJAX search is always on.</span> Showing <strong data-results-count>{{ $movies->count() }}</strong> movies.</p>
        </form>

        {{-- Camera tool on the left and the shortlist on the right --}}
        <div class="utility-grid">
            <article class="tool-card" data-camera-tool>
                <h2>Use your camera to save a movie night snapshot.</h2>
                <div class="camera-frame">
                    <video class="camera-preview" data-camera-preview playsinline autoplay muted hidden></video>
                    <img class="camera-shot" data-camera-shot alt="Saved movie night snapshot" hidden>
                    <div class="camera-empty" data-camera-empty>Start the camera to frame a quick photo before the film starts.</div>
                </div>
                <div class="card-actions">
                    <button class="button button-primary" type="button" data-camera-start>Start camera</button>
                    <button class="button button-secondary" type="button" data-camera-capture hidden>Take snapshot</button>
                    <button class="button button-ghost" type="button" data-camera-reset hidden>Use camera again</button>
                </div>
                <p class="status-copy" data-camera-status>Camera access stays in the browser and does not use tracking.</p>
            </article>

            <article class="tool-card">
                <h2>Saved shortlist</h2>
                <div class="planner-list" data-planner-list>
                    <p class="planner-empty">No films saved yet.</p>
                </div>
            </article>
        </div>

        {{-- Main movie results grid --}}
        <div class="movie-grid" data-movie-grid>
            @foreach ($movies as $movie)
                @include('partials.movie-card', ['movie' => $movie])
            @endforeach
        </div>
    </section>
@endsection
