@extends('layouts.app')

@section('title', 'ReelRoute | Movies')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
@endpush

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <h1>Browse movies and plan your next cinema trip.</h1>
            </div>
        </div>

        <form class="filters-card" data-catalogue-form action="{{ route('api.movies.search') }}" method="get">
            <div class="field-group">
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

            <p class="results-note">AJAX search is always on. Showing <strong data-results-count>{{ $movies->count() }}</strong> movies.</p>
        </form>

        <div class="utility-grid">
            <article class="tool-card" id="trip-tools" data-nearby-tool>
                <h2>Use your location to find nearby cinemas.</h2>
                <button class="button button-primary" type="button" data-locate-button>Use my current location</button>
                <div class="map-frame" data-map></div>
                <p class="status-copy" data-location-status>Waiting for a location request.</p>
                <div class="cinema-list" data-cinema-list>
                    <p>No live cinema results yet.</p>
                </div>
            </article>

            <article class="tool-card">
                <h2>Saved shortlist</h2>
                <div class="planner-list" data-planner-list>
                    <p class="planner-empty">No films saved yet.</p>
                </div>
            </article>
        </div>

        <div class="movie-grid" data-movie-grid>
            @foreach ($movies as $movie)
                @include('partials.movie-card', ['movie' => $movie])
            @endforeach
        </div>
    </section>
@endsection
