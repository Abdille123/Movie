<?php

namespace Database\Seeders;

use App\Models\Movie;
use Carbon\CarbonImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $movies = [
            [
                'title' => 'Midnight Runway',
                'slug' => 'midnight-runway',
                'tagline' => 'Fashion, ambition, and one risky final showing.',
                'synopsis' => 'An anxious costume assistant discovers a lost reel from a legendary director and races to stage a rooftop screening before the studio buries it forever.',
                'genre' => 'Drama',
                'release_year' => 2024,
                'runtime_minutes' => 114,
                'age_rating' => '12A',
                'critic_score' => 91,
                'audience_score' => 88,
                'tone' => 'ember',
                'featured' => true,
                'showtimes' => [
                    ['venue_name' => 'Lighthouse Cinema', 'city' => 'Wolverhampton', 'starts_at' => CarbonImmutable::now()->addDay()->setTime(18, 45), 'screen_format' => '2D', 'available_seats' => 42, 'ticket_price' => 9.50],
                    ['venue_name' => 'Starlane Screens', 'city' => 'Birmingham', 'starts_at' => CarbonImmutable::now()->addDays(2)->setTime(20, 15), 'screen_format' => 'IMAX', 'available_seats' => 18, 'ticket_price' => 13.00],
                ],
                'reviews' => [
                    ['author_name' => 'Aisha', 'rating' => 5, 'comment' => 'It feels stylish without losing the emotional thread.', 'favourite_scene' => 'The rooftop projector reveal', 'would_rewatch' => true],
                    ['author_name' => 'Mason', 'rating' => 4, 'comment' => 'Great pacing and a really confident ending.', 'favourite_scene' => 'The silent fitting-room montage', 'would_rewatch' => true],
                ],
            ],
            [
                'title' => 'Signal in the Rain',
                'slug' => 'signal-in-the-rain',
                'tagline' => 'A weather satellite, a broken radio, and a second chance.',
                'synopsis' => 'When a student radio producer intercepts a weather warning before anyone else, she must convince her estranged father and an entire town to listen.',
                'genre' => 'Thriller',
                'release_year' => 2023,
                'runtime_minutes' => 108,
                'age_rating' => '12A',
                'critic_score' => 86,
                'audience_score' => 84,
                'tone' => 'storm',
                'featured' => true,
                'showtimes' => [
                    ['venue_name' => 'Broadway House', 'city' => 'Wolverhampton', 'starts_at' => CarbonImmutable::now()->addDay()->setTime(21, 5), 'screen_format' => '2D', 'available_seats' => 27, 'ticket_price' => 10.00],
                    ['venue_name' => 'Arc Central', 'city' => 'Coventry', 'starts_at' => CarbonImmutable::now()->addDays(3)->setTime(19, 10), 'screen_format' => 'Dolby', 'available_seats' => 33, 'ticket_price' => 12.50],
                ],
                'reviews' => [
                    ['author_name' => 'Priya', 'rating' => 4, 'comment' => 'The sound design sells every storm warning.', 'favourite_scene' => 'The first radio transmission', 'would_rewatch' => true],
                ],
            ],
            [
                'title' => 'Orbit Cafe',
                'slug' => 'orbit-cafe',
                'tagline' => 'Small kitchen. Big dreams. Zero gravity attitude.',
                'synopsis' => 'A chef with a failing roadside cafe rebrands it as a retro sci-fi diner and unexpectedly becomes the meeting point for a film club, a first date, and a local election campaign.',
                'genre' => 'Comedy',
                'release_year' => 2025,
                'runtime_minutes' => 102,
                'age_rating' => 'PG',
                'critic_score' => 82,
                'audience_score' => 90,
                'tone' => 'mint',
                'featured' => true,
                'showtimes' => [
                    ['venue_name' => 'Screen Yard', 'city' => 'Walsall', 'starts_at' => CarbonImmutable::now()->addDays(2)->setTime(17, 30), 'screen_format' => '2D', 'available_seats' => 64, 'ticket_price' => 8.50],
                    ['venue_name' => 'Cine Quarters', 'city' => 'Birmingham', 'starts_at' => CarbonImmutable::now()->addDays(4)->setTime(14, 20), 'screen_format' => 'Family', 'available_seats' => 71, 'ticket_price' => 7.80],
                ],
                'reviews' => [
                    ['author_name' => 'Hugo', 'rating' => 5, 'comment' => 'Warm, funny, and much sharper than the title suggests.', 'favourite_scene' => 'The pancake launch experiment', 'would_rewatch' => true],
                ],
            ],
            [
                'title' => 'Tin City Echo',
                'slug' => 'tin-city-echo',
                'tagline' => 'Every wall in the city remembers something.',
                'synopsis' => 'A location recordist returns home to archive disappearing industrial spaces, only to uncover audio evidence tied to an unsolved disappearance.',
                'genre' => 'Mystery',
                'release_year' => 2022,
                'runtime_minutes' => 117,
                'age_rating' => '15',
                'critic_score' => 89,
                'audience_score' => 79,
                'tone' => 'slate',
                'featured' => false,
                'showtimes' => [
                    ['venue_name' => 'Warehouse Picturehouse', 'city' => 'Wolverhampton', 'starts_at' => CarbonImmutable::now()->addDays(5)->setTime(20, 0), 'screen_format' => '2D', 'available_seats' => 29, 'ticket_price' => 11.00],
                ],
                'reviews' => [
                    ['author_name' => 'Leah', 'rating' => 4, 'comment' => 'Quietly intense and brilliantly textured.', 'favourite_scene' => 'The empty factory playback', 'would_rewatch' => false],
                ],
            ],
            [
                'title' => 'Sunset Subroutine',
                'slug' => 'sunset-subroutine',
                'tagline' => 'Romance, robotics, and one terrible launch demo.',
                'synopsis' => 'Two junior engineers fake confidence during a live product launch and end up documenting the funniest failure in startup history.',
                'genre' => 'Romance',
                'release_year' => 2024,
                'runtime_minutes' => 99,
                'age_rating' => '12A',
                'critic_score' => 78,
                'audience_score' => 85,
                'tone' => 'sunset',
                'featured' => false,
                'showtimes' => [
                    ['venue_name' => 'The Electric', 'city' => 'Birmingham', 'starts_at' => CarbonImmutable::now()->addDays(3)->setTime(18, 10), 'screen_format' => '2D', 'available_seats' => 56, 'ticket_price' => 9.90],
                ],
                'reviews' => [
                    ['author_name' => 'Imran', 'rating' => 4, 'comment' => 'The chemistry carries it all the way through.', 'favourite_scene' => 'The failed demo apology', 'would_rewatch' => true],
                ],
            ],
            [
                'title' => 'Northern Edit',
                'slug' => 'northern-edit',
                'tagline' => 'A documentary team rewrites the story live on air.',
                'synopsis' => 'An editor on a regional documentary project discovers her interview subject has been lying for months and must rebuild the film in the final 24 hours.',
                'genre' => 'Documentary',
                'release_year' => 2021,
                'runtime_minutes' => 94,
                'age_rating' => 'PG',
                'critic_score' => 83,
                'audience_score' => 81,
                'tone' => 'cobalt',
                'featured' => false,
                'showtimes' => [
                    ['venue_name' => 'Forum Screen', 'city' => 'Coventry', 'starts_at' => CarbonImmutable::now()->addDays(6)->setTime(16, 40), 'screen_format' => '2D', 'available_seats' => 47, 'ticket_price' => 8.20],
                ],
                'reviews' => [
                    ['author_name' => 'Noah', 'rating' => 4, 'comment' => 'Smart and restrained, with a strong final act.', 'favourite_scene' => 'The overnight re-cut', 'would_rewatch' => true],
                ],
            ],
        ];

        foreach ($movies as $movieData) {
            $showtimes = $movieData['showtimes'];
            $reviews = $movieData['reviews'];

            unset($movieData['showtimes'], $movieData['reviews']);

            $movie = Movie::query()->create($movieData);
            $movie->showtimes()->createMany($showtimes);
            $movie->reviews()->createMany($reviews);
        }
    }
}
