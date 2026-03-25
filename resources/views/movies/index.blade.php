@extends('layouts.app')

@section('title', 'ReelRoute | Movies')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
@endpush

@section('content')
    <section class="section-block catalogue-header">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Catalogue and trip tools</span>
                <h1>Browse movies, then use live helpers to plan where and when to watch them.</h1>
            </div>
        </div>

        <div class="catalogue-layout">
            <div>
                <form class="filters-card" data-catalogue-form action="{{ route('api.movies.search') }}" method="get">
                    <div class="field-group">
                        <label for="search">Search movies</label>
                        <input id="search" name="q" type="search" placeholder="Search by title, tagline or genre" autocomplete="off">
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

                    <p class="results-note">Showing <strong data-results-count>{{ $movies->count() }}</strong> movies.</p>
                </form>

                <div class="movie-grid" data-movie-grid>
                    @foreach ($movies as $movie)
                        @include('partials.movie-card', ['movie' => $movie])
                    @endforeach
                </div>
            </div>

            <aside class="tools-stack" id="trip-tools" data-nearby-tool>
                <article class="tool-card">
                    <span class="eyebrow">Hardware API</span>
                    <h2>Use your location to find nearby cinemas.</h2>
                    <p>The cinema finder uses browser geolocation and a live map so the mobile version is genuinely useful.</p>
                    <button class="button button-primary" type="button" data-locate-button>Use my current location</button>
                    <p class="status-copy" data-location-status>Waiting for a location request.</p>
                </article>

                <article class="tool-card weather-card" data-weather-panel>
                    <span class="eyebrow">Weather API</span>
                    <h2>Cinema trip forecast</h2>
                    <p>Weather details will appear here after location is available.</p>
                </article>

                <article class="tool-card">
                    <span class="eyebrow">Nearby cinemas</span>
                    <div class="cinema-list" data-cinema-list>
                        <p>No live cinema results yet.</p>
                    </div>
                    <div class="map-frame" data-map></div>
                </article>

                <article class="tool-card">
                    <span class="eyebrow">Saved shortlist</span>
                    <div class="planner-list" data-planner-list>
                        <p class="planner-empty">No films saved yet.</p>
                    </div>
                </article>
            </aside>
        </div>
    </section>
@endsection
