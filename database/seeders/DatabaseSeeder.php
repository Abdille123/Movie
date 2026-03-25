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
                'imdb_id' => 'tt1375666',
                'title' => 'Inception',
                'slug' => 'inception-tt1375666',
                'tagline' => 'Directed by Christopher Nolan',
                'synopsis' => 'A professional thief enters layered dream worlds to plant an idea in a target who should never know it happened.',
                'genre' => 'Sci-Fi',
                'director' => 'Christopher Nolan',
                'release_year' => 2010,
                'runtime_minutes' => 148,
                'age_rating' => '12A',
                'critic_score' => 74,
                'audience_score' => 88,
                'tone' => 'cobalt',
                'featured' => true,
                'showtimes' => [
                    ['venue_name' => 'Lighthouse Cinema', 'city' => 'Wolverhampton', 'starts_at' => CarbonImmutable::now()->addDay()->setTime(18, 45), 'screen_format' => '2D', 'available_seats' => 42, 'ticket_price' => 9.50],
                    ['venue_name' => 'Starlane Screens', 'city' => 'Birmingham', 'starts_at' => CarbonImmutable::now()->addDays(2)->setTime(20, 15), 'screen_format' => 'IMAX', 'available_seats' => 18, 'ticket_price' => 13.00],
                ],
                'reviews' => [
                    ['author_name' => 'Aisha', 'rating' => 5, 'comment' => 'The visual scale still works brilliantly on a cinema screen.', 'favourite_scene' => 'The folding city sequence', 'would_rewatch' => true],
                    ['author_name' => 'Mason', 'rating' => 4, 'comment' => 'Still one of the easiest crowd-pleasers for a film night.', 'favourite_scene' => 'The hotel corridor fight', 'would_rewatch' => true],
                ],
            ],
            [
                'imdb_id' => 'tt0816692',
                'title' => 'Interstellar',
                'slug' => 'interstellar-tt0816692',
                'tagline' => 'Directed by Christopher Nolan',
                'synopsis' => 'A former pilot joins a mission through a wormhole to search for a future home for humanity.',
                'genre' => 'Sci-Fi',
                'director' => 'Christopher Nolan',
                'release_year' => 2014,
                'runtime_minutes' => 169,
                'age_rating' => '12A',
                'critic_score' => 74,
                'audience_score' => 87,
                'tone' => 'storm',
                'featured' => true,
                'showtimes' => [
                    ['venue_name' => 'Broadway House', 'city' => 'Wolverhampton', 'starts_at' => CarbonImmutable::now()->addDay()->setTime(21, 5), 'screen_format' => '2D', 'available_seats' => 27, 'ticket_price' => 10.00],
                    ['venue_name' => 'Arc Central', 'city' => 'Coventry', 'starts_at' => CarbonImmutable::now()->addDays(3)->setTime(19, 10), 'screen_format' => 'Dolby', 'available_seats' => 33, 'ticket_price' => 12.50],
                ],
                'reviews' => [
                    ['author_name' => 'Priya', 'rating' => 4, 'comment' => 'Huge scale, big emotion, and a soundtrack that belongs in a cinema.', 'favourite_scene' => 'Docking sequence', 'would_rewatch' => true],
                ],
            ],
            [
                'imdb_id' => 'tt0468569',
                'title' => 'The Dark Knight',
                'slug' => 'the-dark-knight-tt0468569',
                'tagline' => 'Directed by Christopher Nolan',
                'synopsis' => 'Batman faces escalating chaos in Gotham as the Joker forces the city into a brutal moral test.',
                'genre' => 'Action',
                'director' => 'Christopher Nolan',
                'release_year' => 2008,
                'runtime_minutes' => 152,
                'age_rating' => '12A',
                'critic_score' => 84,
                'audience_score' => 90,
                'tone' => 'slate',
                'featured' => true,
                'showtimes' => [
                    ['venue_name' => 'Screen Yard', 'city' => 'Walsall', 'starts_at' => CarbonImmutable::now()->addDays(2)->setTime(17, 30), 'screen_format' => '2D', 'available_seats' => 64, 'ticket_price' => 8.50],
                    ['venue_name' => 'Cine Quarters', 'city' => 'Birmingham', 'starts_at' => CarbonImmutable::now()->addDays(4)->setTime(14, 20), 'screen_format' => 'Family', 'available_seats' => 71, 'ticket_price' => 7.80],
                ],
                'reviews' => [
                    ['author_name' => 'Hugo', 'rating' => 5, 'comment' => 'Still one of the safest choices if you want something everyone will watch.', 'favourite_scene' => 'Interrogation room scene', 'would_rewatch' => true],
                ],
            ],
            [
                'imdb_id' => 'tt3783958',
                'title' => 'La La Land',
                'slug' => 'la-la-land-tt3783958',
                'tagline' => 'Directed by Damien Chazelle',
                'synopsis' => 'A musician and an actor fall in love while trying to build creative careers in Los Angeles.',
                'genre' => 'Romance',
                'director' => 'Damien Chazelle',
                'release_year' => 2016,
                'runtime_minutes' => 128,
                'age_rating' => '12A',
                'critic_score' => 94,
                'audience_score' => 80,
                'tone' => 'sunset',
                'featured' => false,
                'showtimes' => [
                    ['venue_name' => 'Warehouse Picturehouse', 'city' => 'Wolverhampton', 'starts_at' => CarbonImmutable::now()->addDays(5)->setTime(20, 0), 'screen_format' => '2D', 'available_seats' => 29, 'ticket_price' => 11.00],
                ],
                'reviews' => [
                    ['author_name' => 'Leah', 'rating' => 4, 'comment' => 'A strong option when you want something lighter but still memorable.', 'favourite_scene' => 'Opening freeway number', 'would_rewatch' => false],
                ],
            ],
            [
                'imdb_id' => 'tt4633694',
                'title' => 'Spider-Man: Into the Spider-Verse',
                'slug' => 'spider-man-into-the-spider-verse-tt4633694',
                'tagline' => 'Directed by Bob Persichetti, Peter Ramsey and Rodney Rothman',
                'synopsis' => 'Miles Morales discovers multiple Spider-People from different dimensions and has to become one himself.',
                'genre' => 'Animation',
                'director' => 'Bob Persichetti, Peter Ramsey, Rodney Rothman',
                'release_year' => 2018,
                'runtime_minutes' => 117,
                'age_rating' => '12A',
                'critic_score' => 87,
                'audience_score' => 84,
                'tone' => 'mint',
                'featured' => false,
                'showtimes' => [
                    ['venue_name' => 'The Electric', 'city' => 'Birmingham', 'starts_at' => CarbonImmutable::now()->addDays(3)->setTime(18, 10), 'screen_format' => '2D', 'available_seats' => 56, 'ticket_price' => 9.90],
                ],
                'reviews' => [
                    ['author_name' => 'Imran', 'rating' => 4, 'comment' => 'Looks fantastic and always gets a good reaction from a group.', 'favourite_scene' => 'Leap of faith', 'would_rewatch' => true],
                ],
            ],
            [
                'imdb_id' => 'tt1160419',
                'title' => 'Dune',
                'slug' => 'dune-tt1160419',
                'tagline' => 'Directed by Denis Villeneuve',
                'synopsis' => 'Paul Atreides arrives on Arrakis and is drawn into a conflict that could reshape the fate of the empire.',
                'genre' => 'Sci-Fi',
                'director' => 'Denis Villeneuve',
                'release_year' => 2021,
                'runtime_minutes' => 155,
                'age_rating' => '12A',
                'critic_score' => 74,
                'audience_score' => 83,
                'tone' => 'ember',
                'featured' => false,
                'showtimes' => [
                    ['venue_name' => 'Forum Screen', 'city' => 'Coventry', 'starts_at' => CarbonImmutable::now()->addDays(6)->setTime(16, 40), 'screen_format' => '2D', 'available_seats' => 47, 'ticket_price' => 8.20],
                ],
                'reviews' => [
                    ['author_name' => 'Noah', 'rating' => 4, 'comment' => 'Huge visuals and a good fit for the site now that movies can sync from IMDb-linked data.', 'favourite_scene' => 'Arrival on Arrakis', 'would_rewatch' => true],
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
