<?php

namespace Tests\Feature;

use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * These tests check the main movie site features.
 */
class MovieSiteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Start each test with the sample app data in place.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /**
     * Make sure the root URL now points users to the movies page.
     */
    public function test_home_route_redirects_to_movies_page(): void
    {
        $this->get('/')
            ->assertRedirect(route('movies.index'));
    }

    /**
     * Make sure the main movies page loads and shows sample content.
     */
    public function test_movies_page_loads_seeded_content(): void
    {
        $response = $this->get(route('movies.index'));

        $response
            ->assertOk()
            ->assertSee('Browse movies and plan your next cinema trip.')
            ->assertSee('Inception');
    }

    /**
     * Make sure movie search returns the right filtered result.
     */
    public function test_catalogue_search_returns_filtered_html(): void
    {
        $response = $this->getJson('/api/movies/search?q=inception');

        $response
            ->assertOk()
            ->assertJsonPath('count', 1);

        $this->assertStringContainsString('Inception', $response->json('html'));
    }

    /**
     * Make sure the AJAX review form saves a review.
     */
    public function test_review_can_be_posted_with_ajax(): void
    {
        $movie = Movie::query()->firstOrFail();

        $response = $this->postJson(route('movies.reviews.store', $movie), [
            'author_name' => 'Sam',
            'rating' => 5,
            'comment' => 'Excellent pacing.',
            'favourite_scene' => 'The final reveal',
            'would_rewatch' => true,
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('message', 'Review added.');

        $this->assertDatabaseHas('reviews', [
            'movie_id' => $movie->id,
            'author_name' => 'Sam',
        ]);
    }

    /**
     * Make sure the weather API response is turned into simple app data.
     */
    public function test_weather_api_transforms_response(): void
    {
        Http::fake([
            'api.open-meteo.com/*' => Http::response([
                'current' => [
                    'temperature_2m' => 13.4,
                    'weather_code' => 3,
                    'wind_speed_10m' => 17.5,
                ],
                'hourly' => [
                    'precipitation_probability' => [10, 20, 15, 5],
                ],
            ]),
        ]);

        $response = $this->getJson('/api/weather?lat=52.58&lng=-2.12');

        $response
            ->assertOk()
            ->assertJsonPath('summary', 'Partly cloudy')
            ->assertJsonPath('temperature', 13);
    }

    /**
     * Make sure nearby cinema data is cleaned up correctly.
     */
    public function test_cinema_api_transforms_nearby_results(): void
    {
        Http::fake([
            'overpass-api.de/*' => Http::response([
                'elements' => [
                    [
                        'lat' => 52.587,
                        'lon' => -2.128,
                        'tags' => [
                            'name' => 'Sample Cinema',
                            'addr:street' => 'Queen Street',
                            'addr:city' => 'Wolverhampton',
                        ],
                    ],
                ],
            ]),
        ]);

        $response = $this->getJson('/api/cinemas/nearby?lat=52.58&lng=-2.12');

        $response
            ->assertOk()
            ->assertJsonPath('cinemas.0.name', 'Sample Cinema');
    }

    /**
     * Make sure OMDb search results can be saved into the local database.
     */
    public function test_catalogue_search_can_import_omdb_results(): void
    {
        config()->set('services.omdb.key', 'test-key');

        Http::fake([
            'www.omdbapi.com/*' => function ($request) {
                if ($request['s'] ?? false) {
                    return Http::response([
                        'Response' => 'True',
                        'Search' => [
                            [
                                'Title' => 'The Matrix',
                                'Year' => '1999',
                                'imdbID' => 'tt0133093',
                                'Type' => 'movie',
                                'Poster' => 'https://example.com/matrix.jpg',
                            ],
                        ],
                    ]);
                }

                return Http::response([
                    'Response' => 'True',
                    'Title' => 'The Matrix',
                    'Year' => '1999',
                    'Rated' => '15',
                    'Runtime' => '136 min',
                    'Genre' => 'Action, Sci-Fi',
                    'Director' => 'Lana Wachowski, Lilly Wachowski',
                    'Plot' => 'A computer hacker learns the world he knows is a simulation.',
                    'Poster' => 'https://example.com/matrix.jpg',
                    'Metascore' => '73',
                    'imdbRating' => '8.7',
                    'imdbID' => 'tt0133093',
                ]);
            },
        ]);

        $response = $this->getJson('/api/movies/search?q=matrix');

        $response
            ->assertOk()
            ->assertJsonPath('count', 1);

        $this->assertDatabaseHas('movies', [
            'imdb_id' => 'tt0133093',
            'title' => 'The Matrix',
        ]);
    }
}
