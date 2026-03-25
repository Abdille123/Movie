<?php

namespace Tests\Feature;

use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MovieSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_home_page_loads_seeded_content(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Plan a film night')
            ->assertSee('Midnight Runway');
    }

    public function test_catalogue_search_returns_filtered_html(): void
    {
        $response = $this->getJson('/api/movies/search?q=signal');

        $response
            ->assertOk()
            ->assertJsonPath('count', 1);

        $this->assertStringContainsString('Signal in the Rain', $response->json('html'));
    }

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
}
